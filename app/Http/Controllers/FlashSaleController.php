<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Services\FlashSaleService;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function __construct(protected FlashSaleService $flashSaleService)
    {
    }

    /**
     * Display all flash sales
     */
    public function index()
    {
        $liveSales = $this->flashSaleService->getLiveSales();
        $upcomingSales = $this->flashSaleService->getUpcomingSales();

        return view('flash-sales.index', compact('liveSales', 'upcomingSales'));
    }

    /**
     * Display a specific flash sale
     */
    public function show(FlashSale $flashSale)
    {
        if (!$flashSale->is_active) {
            abort(404);
        }

        $flashSale->load(['products' => fn($q) => $q->where('status', 'active')->with('category')]);

        return view('flash-sales.show', compact('flashSale'));
    }

    /**
     * Get flash sale data for AJAX (countdown updates)
     */
    public function getData(FlashSale $flashSale)
    {
        return response()->json([
            'is_live' => $flashSale->isLive(),
            'has_ended' => $flashSale->hasEnded(),
            'time_remaining' => $flashSale->time_remaining,
            'products' => $flashSale->flashSaleProducts->map(fn($p) => [
                'product_id' => $p->product_id,
                'quantity_sold' => $p->quantity_sold,
                'remaining' => $p->remaining_quantity,
                'sold_percentage' => $p->sold_percentage,
                'has_stock' => $p->hasStock(),
            ]),
        ]);
    }
}
