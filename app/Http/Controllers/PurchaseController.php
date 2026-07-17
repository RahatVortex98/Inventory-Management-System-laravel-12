<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class PurchaseController extends Controller
{
    public function purchaseList()
    {
        $purchases = Purchase::with('supplier','user')->latest()->paginate(15);
        return view('admin.purchase.purchase-list', compact('purchases'));
    }

    public function purchaseCreate()
    {
        $suppliers = Suppliers::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        return view('admin.purchase.purchase-create', compact('suppliers','products'));
    }

    public function purchaseStore(Request $request)
    {
        $attr = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($attr) {
            $total = collect($attr['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);

            $purchase = Purchase::create([
                'supplier_id' => $attr['supplier_id'],
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'received',
            ]);

            foreach ($attr['items'] as $item) {
                $purchase->items()->create($item);

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'purchase',
                    'reference_id' => $purchase->id,
                ]);
            }
        });

        return redirect()->route('admin.purchaseList')->with('success','Purchase recorded, stock updated');
    }
}