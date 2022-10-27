<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function lists()
    {
        $products = Product::all();

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'sku' => 'required',
            'harga_jual' => 'required',
            'brand' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input['variasi'] = json_encode(array(
            'nama' => $input['name'],
            'sku' => $input['sku'],
            'harga_jual' => $input['harga_jual']
        ));

        $product = Product::create($input);

        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    public function destroy(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::find($request->get('id'));

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
