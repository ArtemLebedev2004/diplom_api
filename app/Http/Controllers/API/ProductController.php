<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\AddProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = ProductResource::collection(Product::all());

        return response()->json([
            'content' => $products
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProductRequest $request)
    {

        $product = Product::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price')
        ]);

        return response()->json([
            'content' => [
                'id' => $product->id,
                'message' => 'Товар добавлен'
            ]
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $product = Product::findOrFail($id);

        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->price = $request->input('price');

        $product->save();

        return response()->json([
            'content' => [
                'id' => $id,
                'message' => 'Данные обновлены'
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'content' => [
               'message' => 'Товар удалён'
            ]
        ]);
    }
}
