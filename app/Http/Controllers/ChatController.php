<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Services\AIChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected AIChatService $aiService;

    public function __construct(AIChatService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Get or create conversation and return recent messages
     */
    public function getConversation(Request $request)
    {
        $conversation = ChatConversation::getOrCreate(
            auth()->id(),
            session()->getId()
        );

        $messages = $conversation->getRecentMessages(20);

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $messages->map(fn($m) => [
                'role' => $m->role,
                'content' => $m->content,
                'created_at' => $m->created_at->diffForHumans(),
            ]),
        ]);
    }

    /**
     * Send message and get AI response
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $conversation = ChatConversation::getOrCreate(
            auth()->id(),
            session()->getId()
        );

        // Save user message
        $userMessage = $conversation->addMessage('user', $request->message);

        // Generate AI response
        $aiResponse = $this->aiService->generateResponse($conversation, $request->message);

        // Save AI response
        $assistantMessage = $conversation->addMessage('assistant', $aiResponse);

        return response()->json([
            'user_message' => [
                'role' => 'user',
                'content' => $userMessage->content,
                'created_at' => $userMessage->created_at->diffForHumans(),
            ],
            'assistant_message' => [
                'role' => 'assistant',
                'content' => $assistantMessage->content,
                'created_at' => $assistantMessage->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Clear conversation history
     */
    public function clearConversation(Request $request)
    {
        $conversation = ChatConversation::getOrCreate(
            auth()->id(),
            session()->getId()
        );

        $conversation->messages()->delete();
        $conversation->update(['status' => 'closed']);

        return response()->json(['success' => true]);
    }
}
