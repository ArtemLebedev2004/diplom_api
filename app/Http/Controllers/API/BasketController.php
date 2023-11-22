<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
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
        $userProducts = Auth::user()->products;

        return response()->json([
           'content' => $userProducts
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $product_id)
    {

        $user = User::findOrFail(Auth::id());

        $product = Product::findOrFail($product_id);

        $user->products()->attach($product);

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

        $user = User::findOrFail(Auth::id());

        if (count($user->products->where('id', '=', $product_id))) {

            $user->products()->detach($product_id);

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
