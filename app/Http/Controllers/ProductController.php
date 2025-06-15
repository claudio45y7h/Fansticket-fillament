<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function bulkstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.customer_id' => 'nullable|integer|exists:customers,id',
            'products.*.section' => 'nullable|string',
            'products.*.row' => 'nullable|string',
            'products.*.seat' => 'nullable|string',
            'products.*.info' => 'required|string',
            'products.*.type' => 'required|string',
            'products.*.stock' => 'required|integer',
            'products.*.price' => 'required|numeric',
            'products.*.gate' => 'nullable|string',
            'products.*.barcode' => 'nullable|string',
            'products.*.status' => 'required|string|in:transferir,pendiente,transferido',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $created = [];
        foreach ($request->products as $data) {
            if (empty($data['barcode'])) {
                $data['barcode'] = Product::generateBarcode();
            }
            $created[] = Product::create($data);
        }
        return response()->json($created, 201);
    }

    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|string|exists:products,id',
            'status' => 'required|string|in:pendiente,transferir,transferido',
            'name' => 'required|string',
            'lastname' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $customer = \App\Models\Customer::where('email', $request->email)->first();
        if (!$customer) {
            $customer = \App\Models\Customer::create([
                'name' => $request->name,
                'last_name' => $request->lastname,
                'email' => $request->email,
                'activo' => true
            ]);
        }
        $updated = [];
        foreach ($request->product_ids as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->customer_id = $customer->id;
                $product->status = $request->status;
                $product->save();
                $updated[] = $product;
            }
        }
        return response()->json([
            'message' => 'Productos transferidos correctamente',
            'products' => $updated,
            'customer' => $customer
        ]);
    }
}
