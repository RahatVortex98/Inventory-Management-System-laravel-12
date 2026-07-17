<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class UserDashboardController extends Controller
{
  public function index(){
    $todaySalesCount = Sale::where('user_id', Auth::id())->whereDate('created_at', today())->count();
    return view('user.dashboard', compact('todaySalesCount'));
}
    




    public function saleCreate()
    {
        $products = Product::where('status', 1)->where('current_stock', '>', 0)->get();
        return view('user.sale.sale-create', compact('products'));
    }

    public function saleStore(Request $request)
    {
        $attr = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($attr) {
            $total = 0;
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'customer_name' => $attr['customer_name'] ?? null,
                'payment_method' => $attr['payment_method'],
                'total_amount' => 0,
            ]);

            foreach ($attr['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->current_stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price, // price locked at sale time
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                ]);

                $total += $product->selling_price * $item['quantity'];
            }

            $sale->update(['total_amount' => $total]);
        });

        return redirect()->route('user.saleList')->with('success','Sale completed');
    }

    public function saleList()
    {
        // staff sees only their own sales; admin sees all — enforce in policy/middleware later
        $sales = Sale::with('user')->where('user_id', Auth::id())->latest()->paginate(15);
        return view('user.sale.sale-list', compact('sales'));
    }
}

