<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductQuestion;
use App\Models\ProductAnswer;
use Illuminate\Http\Request;

class ProductQAController extends Controller
{
    public function storeQuestion(Request $request, Product $product)
    {
        $request->validate([
            'question' => 'required|string|min:10|max:500',
        ]);

        ProductQuestion::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'question' => $request->question,
            'is_approved' => false, // Requires moderation
        ]);

        return back()->with('success', 'Your question has been submitted and will appear after review.');
    }

    public function storeAnswer(Request $request, ProductQuestion $question)
    {
        $request->validate([
            'answer' => 'required|string|min:10|max:1000',
        ]);

        $isSeller = false;
        if (auth()->user()->seller) {
            $product = $question->product;
            $isSeller = $product->seller_id === auth()->user()->seller->id;
        }

        ProductAnswer::create([
            'question_id' => $question->id,
            'user_id' => auth()->id(),
            'answer' => $request->answer,
            'is_seller_answer' => $isSeller,
            'is_approved' => $isSeller, // Auto-approve seller answers
        ]);

        $message = $isSeller 
            ? 'Your answer has been posted.' 
            : 'Your answer has been submitted and will appear after review.';

        return back()->with('success', $message);
    }

    public function voteAnswer(Request $request, ProductAnswer $answer)
    {
        $answer->increment('helpful_count');
        
        return response()->json([
            'success' => true,
            'helpful_count' => $answer->fresh()->helpful_count
        ]);
    }
}
