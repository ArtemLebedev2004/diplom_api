<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $userProducts = DB::table('product_user')->where('user_id', '=', Auth::id())
        // ->join('products', 'product_user.product_id', '=', 'products.id')
        // ->select('product_user.id', 'products.id as product_id', 'products.price', 'products.title')
        // ->get();

        // $userProducts = CartItem::with('product')->get();

        $userProducts = CartItemResource::collection(CartItem::whereRelation('cart', 'user_id', Auth::id())->get());

        return response()->json([
           'content' => $userProducts
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $product_id)
    {

        $cart = Cart::create([
            'user_id' => Auth::id()
        ]);

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product_id
        ]);

        // $user = User::findOrFail(Auth::id());

        // $product = Product::findOrFail($product_id);

        // $user->products()->attach($product);

        return response()->json([
            'content' => [
                'message' => 'Товар в корзине'
            ]
        ], 200);

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
    public function destroy(string $product_id)
    {

        $cartItem = CartItem::whereRelation('cart', 'user_id', Auth::id())->where('product_id', $product_id)->get()->first();

        if ($cartItem) {

            CartItem::find($cartItem->id)->first()->delete();

            $cart = Cart::has('cartItems')->get();

            if (!count($cart)) {
                Cart::where('user_id', Auth::id())->delete();
            }

            return response()->json([
                'content' => [
                    'message' => 'Позиция удалена из корзины'
                ]
            ]);

        } else {

            return response()->json([
                'warning' => [
                    'code' => 404,
                    'message' => 'Не найдено'
                ]
            ]);

        }

    }
}
