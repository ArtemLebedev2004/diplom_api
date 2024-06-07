<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class typecontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = type::all();

        return response()->json($types, 200);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $title)
    {
        // return response()->json('wreer', 200);

        $products = DB::table('products')->where('type', $title)->latest()->get();

        return response()->json($products, 200);
    }

    public function t(Request $request, string $title2)
    {
        // return response()->json($request->data, 200);
        $ids = explode(',', $request->data);

        // $products = Product::all();
        $sortProduct = DB::table('products')->whereIn('id', $ids)->get();
        // return response()->json($sortProduct, 200);

        // $products = ProductResource::collection(Product::all());
        if ($title2 == 'new') {
            $products = DB::table('products')->whereIn('id', $ids)->orderByDesc('id')->get();
        } else {
            $products = DB::table('products')->whereIn('id', $ids)->orderBy('id')->get();
        }

        return response()->json($products, 200);
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
