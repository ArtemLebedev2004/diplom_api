<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = OrderResource::collection(Order::where('user_id', Auth::id())->get());

        return response()->json([
            'content' => $orders
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $carts = Cart::where('user_id', Auth::id());

        if (count($carts->get())) {

            $order = Order::create([
                'user_id' => Auth::id()
            ]);

            $productsId = CartItem::whereRelation('cart', 'user_id', Auth::id())->pluck('product_id');
            // $countCartItems = count(Cart::has('cartItems')->where('user_id', Auth::id())->get());

            for ($index = 0; $index < count($productsId); $index++) {
                
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productsId[$index]
                ]);

            }

            $cartId = $carts->first()->id;
            CartItem::where('cart_id', $cartId)->delete();
            $carts->delete();

            return response()->json([
                'content' => [
                    'order_id' => $order->id,
                    'message' => 'Заказ оформлен'
                ]
            ], 200);

        } else {

            return response()->json([
                'warning' => [
                    'code' => 422,
                    'message' => 'Нет товаров для оформления'
                ]
            ], 422);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
