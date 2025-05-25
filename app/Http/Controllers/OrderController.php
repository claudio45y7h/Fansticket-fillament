<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Crear una orden usando el user_id proporcionado en el request (sin autenticación por token).
     */
    public function storeByUserId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array',
            'tickets.*' => 'exists:tickets,id',
            'status' => 'required|string|in:pending,completed,cancelled',
            'total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al validar la solicitud',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $userId = $request->user_id;

        $order = Order::create([
            'user_id' => $userId,
            'event_id' => $request->event_id,
            'status' => $request->status,
            'total' => $request->total
        ]);

        // Adjuntar tickets a la orden
        $order->tickets()->attach($request->tickets);

        // Poner stock a 0 para los tickets asociados
        foreach ($request->tickets as $ticketId) {
            Ticket::where('id', $ticketId)->update(['stock' => 0]);
        }

        // Enviar correo de confirmación de compra
        $customerEmail = $request->email ?? ($order->user->email ?? null);
        if ($customerEmail) {
            try {
                \Mail::to($customerEmail)->send(new \App\Mail\OrderConfirmationMail($order));
            } catch (\Exception $e) {
                // Si falla, no interrumpe el flujo
            }
        }

        return response()->json([
            'message' => 'Orden creada exitosamente',
            'data' => $order->load(['tickets', 'event']),
            'status' => 201
        ], 201);
    }
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
                      ->with(['tickets', 'event'])
                      ->get();

        return response()->json([
            'message' => 'Órdenes encontradas',
            'data' => $orders
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array',
            'tickets.*' => 'exists:tickets,id',
            'status' => 'required|string|in:pending,completed,cancelled',
            'total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al validar la solicitud',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        // Permitir user_id opcional (puede ser null si no se envía)
        $userId = Auth::check() ? Auth::id() : ($request->has('user_id') ? $request->user_id : null);

        $order = Order::create([
            'user_id' => $userId,
            'event_id' => $request->event_id,
            'status' => $request->status,
            'total' => $request->total
        ]);

        // Adjuntar tickets a la orden
        $order->tickets()->attach($request->tickets);

        // Poner stock a 0 para los tickets asociados
        foreach ($request->tickets as $ticketId) {
            Ticket::where('id', $ticketId)->update(['stock' => 0]);
        }


        // Enviar correo de confirmación de compra
        $customerEmail = $request->email ?? ($order->user->email ?? null);
        if ($customerEmail) {
            try {
                \Mail::to($customerEmail)->send(new \App\Mail\OrderConfirmationMail($order));
            } catch (\Exception $e) {
                // Si falla, no interrumpe el flujo
            }
        }

        return response()->json([
            'message' => 'Orden creada exitosamente',
            'data' => $order->load(['tickets', 'event']),
            'status' => 201
        ], 201);
    }

    public function getOrderByUserId(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado',
                'status' => 401
            ], 401);
        }

        $orders = Order::where('user_id', $user->id)->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron órdenes para este usuario',
                'status' => 404
            ], 404);
        }

        return response()->json($orders, 200);
    }

    public function show($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
                     ->with(['tickets', 'event'])
                     ->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Orden no encontrada',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'message' => 'Orden encontrada',
            'data' => $order
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Orden no encontrada',
                'status' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'string|in:pending,completed,cancelled',
            'total' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al validar la solicitud',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $order->update($request->all());

        return response()->json([
            'message' => 'Orden actualizada exitosamente',
            'data' => $order->load(['tickets', 'event']),
            'status' => 200
        ], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Orden no encontrada',
                'status' => 404
            ], 404);
        }

        $order->tickets()->detach();
        $order->delete();

        return response()->json([
            'message' => 'Orden eliminada correctamente',
            'status' => 200
        ], 200);
    }

    public function indexView()
    {
        $orders = Order::with(['user', 'event', 'tickets'])->get();
        $events = Event::all();
        $tickets = Ticket::where('stock', '>', 0)->get();
        
        return view('orders.index', compact('orders', 'events', 'tickets'));
    }
}
