<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Orders & Shipping',
                'slug' => 'orders-shipping',
                'icon' => 'truck',
                'description' => 'Questions about placing orders and delivery',
                'sort_order' => 1,
                'faqs' => [
                    [
                        'question' => 'How can I track my order?',
                        'answer' => 'Once your order is shipped, you will receive an email with tracking information. You can also track your order by logging into your account and visiting the "My Orders" section.',
                    ],
                    [
                        'question' => 'What are the shipping charges?',
                        'answer' => 'We offer free shipping on orders above ₹999. For orders below this amount, a flat shipping fee of ₹99 is applicable. Express delivery options are available at additional cost.',
                    ],
                    [
                        'question' => 'How long does delivery take?',
                        'answer' => 'Standard delivery takes 5-7 business days. Express delivery (where available) takes 2-3 business days. Delivery times may vary based on your location and product availability.',
                    ],
                    [
                        'question' => 'Can I change my delivery address after placing an order?',
                        'answer' => 'You can change your delivery address within 24 hours of placing the order by contacting our support team. Once the order is shipped, address changes are not possible.',
                    ],
                ]
            ],
            [
                'name' => 'Returns & Refunds',
                'slug' => 'returns-refunds',
                'icon' => 'refresh',
                'description' => 'Information about our return and refund policies',
                'sort_order' => 2,
                'faqs' => [
                    [
                        'question' => 'What is your return policy?',
                        'answer' => 'We offer a 30-day return policy for most items. Products must be unused, in original packaging, and with all tags attached. Some items like innerwear and personalized products are not eligible for return.',
                    ],
                    [
                        'question' => 'How do I initiate a return?',
                        'answer' => 'To initiate a return, go to "My Orders" in your account, select the order, and click "Return Item". Follow the instructions to schedule a pickup or drop-off at a nearby location.',
                    ],
                    [
                        'question' => 'When will I receive my refund?',
                        'answer' => 'Refunds are processed within 5-7 business days after we receive and inspect the returned item. The amount will be credited to your original payment method.',
                    ],
                    [
                        'question' => 'Can I exchange an item instead of returning it?',
                        'answer' => 'Yes, you can exchange items for a different size or color (subject to availability). Select the "Exchange" option when initiating your return request.',
                    ],
                ]
            ],
            [
                'name' => 'Payment',
                'slug' => 'payment',
                'icon' => 'credit-card',
                'description' => 'Payment methods and billing questions',
                'sort_order' => 3,
                'faqs' => [
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept all major credit/debit cards (Visa, Mastercard, Rupay), UPI, Net Banking, and popular wallets like Paytm and PhonePe. Cash on Delivery is available for select locations.',
                    ],
                    [
                        'question' => 'Is it safe to use my credit card on your website?',
                        'answer' => 'Yes, absolutely. We use industry-standard SSL encryption and partner with Razorpay for secure payment processing. Your card details are never stored on our servers.',
                    ],
                    [
                        'question' => 'Why was my payment declined?',
                        'answer' => 'Payments can be declined due to insufficient funds, incorrect card details, or bank security measures. Please verify your details and try again, or contact your bank for assistance.',
                    ],
                    [
                        'question' => 'Can I use multiple payment methods for one order?',
                        'answer' => 'Currently, we support single payment method per order. However, you can combine store credit or gift cards with other payment methods.',
                    ],
                ]
            ],
            [
                'name' => 'Account & Profile',
                'slug' => 'account-profile',
                'icon' => 'user',
                'description' => 'Managing your account and preferences',
                'sort_order' => 4,
                'faqs' => [
                    [
                        'question' => 'How do I create an account?',
                        'answer' => 'Click on "Sign Up" at the top of the page and enter your email address and create a password. You can also sign up using your Google or Facebook account for faster registration.',
                    ],
                    [
                        'question' => 'I forgot my password. How can I reset it?',
                        'answer' => 'Click on "Forgot Password" on the login page, enter your registered email address, and we\'ll send you an OTP to reset your password.',
                    ],
                    [
                        'question' => 'How do I update my profile information?',
                        'answer' => 'Log into your account and go to "My Profile". Here you can update your personal information, addresses, and communication preferences.',
                    ],
                    [
                        'question' => 'How do I delete my account?',
                        'answer' => 'You can delete your account from the "My Profile" section. Please note that this action is irreversible and all your data including order history will be permanently deleted.',
                    ],
                ]
            ],
            [
                'name' => 'Products',
                'slug' => 'products',
                'icon' => 'shopping-bag',
                'description' => 'Questions about our products',
                'sort_order' => 5,
                'faqs' => [
                    [
                        'question' => 'How do I find my size?',
                        'answer' => 'Each product page has a size guide with detailed measurements. We recommend measuring yourself and comparing with our size chart for the best fit.',
                    ],
                    [
                        'question' => 'Are the product colors accurate?',
                        'answer' => 'We strive to display colors as accurately as possible. However, actual colors may vary slightly due to monitor settings and lighting conditions in photography.',
                    ],
                    [
                        'question' => 'How do I know if an item is in stock?',
                        'answer' => 'Stock availability is shown on each product page. If an item is out of stock, you can click "Notify Me" to receive an email when it\'s back in stock.',
                    ],
                    [
                        'question' => 'Do you offer gift wrapping?',
                        'answer' => 'Yes, we offer premium gift wrapping for an additional ₹99. You can select this option during checkout and add a personalized message.',
                    ],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $faqs = $categoryData['faqs'];
            unset($categoryData['faqs']);
            
            $category = FaqCategory::create($categoryData);
            
            foreach ($faqs as $index => $faqData) {
                $faqData['category_id'] = $category->id;
                $faqData['sort_order'] = $index + 1;
                Faq::create($faqData);
            }
        }
    }
}
