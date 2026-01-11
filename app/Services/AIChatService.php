<?php

namespace App\Services;

use App\Models\ChatConversation;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Faq;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIChatService
{
    protected string $apiKey;
    protected string $provider;
    
    public function __construct()
    {
        $this->provider = config('services.ai_chat.provider', 'gemini');
        $this->apiKey = config('services.ai_chat.api_key', '');
    }

    /**
     * Generate AI response for user message
     */
    public function generateResponse(ChatConversation $conversation, string $userMessage): string
    {
        // Get conversation context
        $context = $this->buildContext($conversation, $userMessage);
        
        // If no API key, use rule-based fallback
        if (empty($this->apiKey)) {
            return $this->getFallbackResponse($userMessage, $context);
        }

        try {
            if ($this->provider === 'gemini') {
                return $this->callGeminiAPI($context, $userMessage);
            } elseif ($this->provider === 'openai') {
                return $this->callOpenAIAPI($context, $userMessage);
            }
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            return $this->getFallbackResponse($userMessage, $context);
        }

        return $this->getFallbackResponse($userMessage, $context);
    }

    /**
     * Build context for AI
     */
    protected function buildContext(ChatConversation $conversation, string $userMessage): array
    {
        $context = [
            'store_name' => 'ShopEase',
            'store_description' => 'Premium fashion and lifestyle e-commerce store',
            'user' => null,
            'recent_orders' => [],
            'categories' => Category::where('is_active', true)->pluck('name')->toArray(),
            'faqs' => Faq::where('is_active', true)->limit(10)->get(['question', 'answer'])->toArray(),
            'policies' => [
                'shipping' => 'Free shipping on orders over Rs. 250. Standard delivery 3-5 business days.',
                'returns' => '30-day return policy. Items must be unused with tags.',
                'payment' => 'We accept all major credit cards, UPI, and net banking via Razorpay.',
            ],
        ];

        // Add user context if logged in
        if ($conversation->user_id) {
            $user = $conversation->user;
            $context['user'] = [
                'name' => $user->name,
                'is_member' => $user->isMember(),
            ];
            
            // Get recent orders
            $context['recent_orders'] = Order::where('user_id', $conversation->user_id)
                ->latest()
                ->limit(3)
                ->get(['order_number', 'status', 'total', 'created_at'])
                ->toArray();
        }

        // Search for relevant products if query seems product-related
        $productKeywords = ['product', 'buy', 'price', 'available', 'stock', 'shirt', 'dress', 'shoe', 'bag', 'accessory'];
        if ($this->containsKeywords($userMessage, $productKeywords)) {
            $context['relevant_products'] = Product::where('status', 'active')
                ->where(function ($q) use ($userMessage) {
                    $q->where('name', 'like', "%{$userMessage}%")
                      ->orWhere('description', 'like', "%{$userMessage}%");
                })
                ->limit(3)
                ->get(['name', 'price', 'discount_price', 'stock'])
                ->toArray();
        }

        return $context;
    }

    /**
     * Call Google Gemini API
     */
    protected function callGeminiAPI(array $context, string $userMessage): string
    {
        $systemPrompt = $this->buildSystemPrompt($context);
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$this->apiKey}", [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\nCustomer: " . $userMessage]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 500,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? $this->getFallbackResponse($userMessage, $context);
        }

        throw new \Exception('Gemini API error: ' . $response->body());
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAIAPI(array $context, string $userMessage): string
    {
        $systemPrompt = $this->buildSystemPrompt($context);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 500,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? $this->getFallbackResponse($userMessage, $context);
        }

        throw new \Exception('OpenAI API error: ' . $response->body());
    }

    /**
     * Build system prompt for AI
     */
    protected function buildSystemPrompt(array $context): string
    {
        $prompt = "You are a helpful customer support assistant for {$context['store_name']}, a {$context['store_description']}.

Your role is to:
- Help customers with product inquiries, orders, shipping, and returns
- Be friendly, professional, and concise
- Provide accurate information based on store policies
- Suggest products when relevant
- Guide customers to support tickets for complex issues

Store Policies:
- Shipping: {$context['policies']['shipping']}
- Returns: {$context['policies']['returns']}
- Payment: {$context['policies']['payment']}

Available Categories: " . implode(', ', $context['categories']) . "

";

        if (!empty($context['user'])) {
            $prompt .= "Customer Name: {$context['user']['name']}\n";
            if ($context['user']['is_member']) {
                $prompt .= "This customer is a premium member.\n";
            }
        }

        if (!empty($context['recent_orders'])) {
            $prompt .= "\nCustomer's Recent Orders:\n";
            foreach ($context['recent_orders'] as $order) {
                $prompt .= "- Order #{$order['order_number']}: Status - {$order['status']}, Total - Rs. {$order['total']}\n";
            }
        }

        if (!empty($context['relevant_products'])) {
            $prompt .= "\nRelevant Products:\n";
            foreach ($context['relevant_products'] as $product) {
                $price = $product['discount_price'] ?? $product['price'];
                $prompt .= "- {$product['name']}: Rs. {$price} (Stock: {$product['stock']})\n";
            }
        }

        $prompt .= "\nKeep responses concise (2-3 sentences max). Be helpful and friendly. If you can't help, suggest creating a support ticket.";

        return $prompt;
    }

    /**
     * Rule-based fallback responses
     */
    protected function getFallbackResponse(string $message, array $context): string
    {
        $message = strtolower($message);

        // Greetings
        if ($this->containsKeywords($message, ['hi', 'hello', 'hey', 'namaste', 'good morning', 'good evening'])) {
            $greeting = $context['user'] ? "Hi {$context['user']['name']}!" : "Hello!";
            return "{$greeting} Welcome to ShopEase. How can I help you today? 😊";
        }

        // Order related
        if ($this->containsKeywords($message, ['order', 'track', 'delivery', 'shipping', 'where is my'])) {
            if (!empty($context['recent_orders'])) {
                $order = $context['recent_orders'][0];
                return "Your most recent order #{$order['order_number']} is currently '{$order['status']}'. You can track all your orders from your account dashboard or use our order tracking page.";
            }
            return "To track your order, please visit the 'My Orders' section in your account or use our order tracking page with your order number and email.";
        }

        // Return/Refund
        if ($this->containsKeywords($message, ['return', 'refund', 'exchange', 'cancel'])) {
            return "We have a 30-day return policy. Items must be unused with original tags. To initiate a return, please go to your order details and click 'Request Return', or create a support ticket for assistance.";
        }

        // Shipping
        if ($this->containsKeywords($message, ['shipping', 'delivery time', 'how long', 'free shipping'])) {
            return "We offer free shipping on orders over Rs. 250! Standard delivery takes 3-5 business days. Express delivery options are available at checkout.";
        }

        // Payment
        if ($this->containsKeywords($message, ['payment', 'pay', 'card', 'upi', 'cod', 'cash on delivery'])) {
            return "We accept all major credit/debit cards, UPI, net banking, and wallets through Razorpay. Currently, we don't offer Cash on Delivery.";
        }

        // Product inquiry
        if ($this->containsKeywords($message, ['product', 'price', 'available', 'stock', 'buy'])) {
            return "You can browse our products in the Shop section. Use filters to find exactly what you're looking for. If you need help finding something specific, let me know!";
        }

        // Membership
        if ($this->containsKeywords($message, ['member', 'membership', 'premium', 'discount', 'benefits'])) {
            return "Our membership program offers exclusive discounts, early access to sales, and free shipping! Visit the Membership page to learn more and subscribe.";
        }

        // Contact/Support
        if ($this->containsKeywords($message, ['contact', 'support', 'help', 'speak', 'human', 'agent'])) {
            return "For detailed assistance, please create a support ticket and our team will respond within 24 hours. You can also check our FAQ section for quick answers.";
        }

        // Thank you
        if ($this->containsKeywords($message, ['thank', 'thanks', 'helpful', 'great'])) {
            return "You're welcome! Is there anything else I can help you with? 😊";
        }

        // Default response
        return "I'm here to help with orders, shipping, returns, and product inquiries. Could you please provide more details about what you need? For complex issues, I recommend creating a support ticket.";
    }

    /**
     * Check if message contains any keywords
     */
    protected function containsKeywords(string $message, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains(strtolower($message), strtolower($keyword))) {
                return true;
            }
        }
        return false;
    }
}
