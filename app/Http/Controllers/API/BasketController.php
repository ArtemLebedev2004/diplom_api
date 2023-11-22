<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $userProducts = Auth::user()->products;

        return response()->json($userProducts, 200);
        
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
    public function destroy(string $id)
    {
        //
    }
}
