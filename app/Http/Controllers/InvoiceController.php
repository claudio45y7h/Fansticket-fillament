<?php


namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'event_id' => 'required|exists:events,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $invoice = Invoice::create([
            'customer_id' => $validated['customer_id'],
            'event_id' => $validated['event_id'],
        ]);

        $productIds = [];
        foreach ($validated['products'] as $item) {
            $product = Product::find($item['id']);
            $invoice->products()->attach($product, ['quantity' => $item['quantity']]);
            $productIds[] = $item['id'];
        }

        // Actualiza el customer_id de los productos asociados
        Product::whereIn('id', $productIds)->update(['customer_id' => $invoice->customer_id]);

        return response()->json($invoice, 201);
    }
}
