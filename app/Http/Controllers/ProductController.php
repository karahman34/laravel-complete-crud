<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->wantsJson()) {
            return view('pages.products', [
                'title' => 'Products'
            ]);
        }

        return DataTables::of(Product::query())
                            ->addColumn('actions', function (Product $product) {
                                $editButton = '<a href="'.route('products.edit', ['product' => $product->id]).'" class="btn btn-warning form-modal-trigger mr-2" data-modal-title="Edit '.$product->name.'" data-modal-action="update"><i class="fas fa-edit"></i></a>';

                                $deleteButton = '<a href="'.route('products.destroy', ['product' => $product->id]).'" class="btn btn-danger delete-prompt-trigger" data-item-name="'.$product->name.'" data-table-selector="#data-table"><i class="fas fa-trash"></i></a>';

                                return $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('components.product.form', [
            'action' => 'create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $payload = $request->only([
            'name',
            'price',
            'status'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $payload['image'] = $image->store(Product::$image_folder);
        }

        $product = Product::create($payload);

        return response()->json([
            'ok' => true,
            'message' => 'Product created.',
            'data' => $product,
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('components.product.form', [
            'action' => 'edit',
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $payload = $request->only([
            'name',
            'price',
            'status'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $payload['image'] = $image->store(Product::$image_folder);

            // Delete old image
            if (!is_null($product->image)) {
                Storage::delete($product->image);
            }
        }

        $product->update($payload);

        return response()->json([
            'ok' => true,
            'message' => 'Product updated.',
            'data' => $product,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (!is_null($product->image)) {
            Storage::delete($product->image);
        }

        $product->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Product deleted.',
            'data' => $product,
        ]);
    }
}
