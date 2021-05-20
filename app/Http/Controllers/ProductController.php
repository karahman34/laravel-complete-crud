<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Helpers\ExportHelper;
use App\Http\Requests\ExportRequest;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\ProductRequest;
use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
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
                            ->editColumn('image', function (Product $product) {
                                return is_null($product->image) ? null : Storage::url($product->image);
                            })
                            ->addColumn('actions', function (Product $product) {
                                $editButton = view('components.datatable.buttons.edit-btn', [
                                    'url' => route('products.edit', ['product' => $product->id]),
                                    'modal' => '.product-form',
                                ]);

                                $deleteButton = view('components.datatable.buttons.delete-btn', [
                                    'url' => route('products.destroy', ['product' => $product->id]),
                                    'dttb' => '#data-table',
                                    'itemName' => $product->name,
                                ]);

                                return $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Export resources.
     *
     * @param   ExportRequest  $exportRequest
     *
     * @return  mixed
     */
    public function export(ExportRequest $exportRequest)
    {
        if ($exportRequest->showView()) {
            return view('components.export-modal', [
                'formats' => $exportRequest->allowed_formats,
                'action' => route('products.export')
            ]);
        }

        return Excel::download(
            new ProductsExport($exportRequest->take),
            ExportHelper::formatFileName('products', $exportRequest->input('format'))
        );
    }

    /**
     * Import and insert data from the file.
     *
     * @param   ImportRequest   $importRequest
     *
     * @return  mixed
     */
    public function import(ImportRequest $importRequest)
    {
        if ($importRequest->showView()) {
            return view('components.import-modal', [
                'action' => route('products.import')
            ]);
        }

        Excel::import(new ProductsImport, $importRequest->file('file'));

        return response()->json([
            'ok' => true,
            'message' => 'Success to import file data.'
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('components.product.form');
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
