<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\AddProductRequest;
use App\Http\Requests\Product\EditProductRequest;
use App\Http\Requests\searchrequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = ProductResource::collection(DB::table('products')->latest()->get());

        return response()->json([
            'content' => $products
        ], 200);

    }

    public function search(searchrequest $request)
    {
        try {
            $product = Product::findOrFail($request->input('input'));

            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 2,
                'message' => 'Проект не найден'
            ], 402);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProductRequest $request)
    {
        $products = ProductResource::collection(Product::all());

        $photo = $request->file('photo');
        $photo = $photo->getClientOriginalName();

        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i]->photo != $photo && $i == count($products) - 1) {
                $request->file('photo')->move(public_path('images/attachments/'), $photo);
            } else if ($products[$i]->photo == $photo) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Фото с таким именем уже существует'
                ], 401);
            }
        }

        if (!count($products)) {
            $request->file('photo')->move(public_path('images/attachments/'), $photo);
        }

        // $types = type::all();

        if(!count(DB::table('types')->where('title', $request->input('type'))->get())) {
            type::create([
                'title' => $request->input('type'),
            ]);
        }
        // return response()->json($types, 200);

        $product = Product::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'date' => $request->input('date'),
            'photo' => $photo
        ]);


        if ($product) {
            return response()->json([
                'content' => [
                    'id' => $product->id,
                    'message' => 'Товар добавлен'
                ]
            ], 201);
        } else {
            return response()->json('WROOOOONG!!!', 402);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'data' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);

        if ($request->file('photo')) {
            $photo = $request->file('photo');
            $photo = $photo->getClientOriginalName();
            $request->file('photo')->move(public_path('images/attachments/'), $photo);
            $product->photo = $photo;
        }


        if ($request->input('title') != $product->title) {
            $product->title = $request->input('title');
        }

        if ($request->input('description') != $product->description) {
            $product->description = $request->input('description');
        }

        if ($request->input('amount') != $product->amount) {
            $product->amount = $request->input('amount');
        }

        if ($request->input('type') != $product->type) {
            $product->type = $request->input('type');
        }

        if ($request->input('date') != $product->date) {
            $product->date = $request->input('date');
        }

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
        $product = Product::findOrFail($id);

        DB::table('types')->where('title', $product->type)->delete();

        $currentImage = public_path() . '/images/attachments/' . $product->photo;

        if (file_exists($currentImage)) {
            unlink($currentImage);
        }

        Product::findOrFail($id)->delete();

        return response()->json([
            'content' => [
               'message' => 'Товар удалён'
            ]
        ]);
    }
}
