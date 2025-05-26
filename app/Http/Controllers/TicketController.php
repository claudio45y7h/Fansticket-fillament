<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
       $tickets = Ticket::all();
       return response()->json($tickets, 200);
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }
        return response()->json($ticket, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer|exists:events,id',
            'section' => 'nullable|string',
            'row' => 'nullable|string',
            'seat' => 'nullable|string',
            'info' => 'required|string',
            'type' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'id' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = Ticket::create($request->all());
        return response()->json([
            'message' => 'Ticket creado exitosamente',
            'ticket' => $ticket
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'section' => 'nullable|string',
            'row' => 'nullable|string',
            'seat' => 'nullable|string',
            'info' => 'string',
            'type' => 'string',
            'stock' => 'integer|min:0',
            'price' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket->update($request->all());
        return response()->json([
            'message' => 'Ticket actualizado exitosamente',
            'ticket' => $ticket
        ], 200);
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket no encontrado'], 404);
        }

        $ticket->delete();
        return response()->json(['message' => 'Ticket eliminado exitosamente'], 200);
    }

    public function getTicketsByEventId($event_id)
    {
        $tickets = Ticket::where('event_id', $event_id)->get();
        if ($tickets->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron tickets para este evento',
                'status' => 404
            ], 404);
        }
        return response()->json($tickets, 200);
    }

    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tickets' => 'required|array|min:1',
            'tickets.*.event_id' => 'required|integer|exists:events,id',
            'tickets.*.section' => 'nullable|string',
            'tickets.*.row' => 'nullable|string',
            'tickets.*.seat' => 'nullable|string',
            'tickets.*.info' => 'required|string',
            'tickets.*.type' => 'required|string',
            'tickets.*.stock' => 'required|integer|min:0',
            'tickets.*.price' => 'required|numeric|min:0',
            'tickets.*.id' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $created = [];
        foreach ($request->tickets as $ticketData) {
            $created[] = Ticket::create($ticketData);
        }

        return response()->json([
            'message' => 'Tickets creados exitosamente',
            'tickets' => $created
        ], 201);
    }

    public function webIndex()
    {
        $tickets = Ticket::with('event')->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function webStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer|exists:events,id',
            'info' => 'required|string',
            'type' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'key' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Ticket::create($request->all());
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket creado exitosamente');
    }

    public function webUpdate(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return redirect()->back()
                ->with('error', 'Ticket no encontrado');
        }

        $validator = Validator::make($request->all(), [
            'info' => 'string',
            'type' => 'string',
            'stock' => 'integer|min:0',
            'price' => 'numeric|min:0',
            'key' => 'string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ticket->update($request->all());
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket actualizado exitosamente');
    }

    public function getOrders($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket no encontrado'], 404);
        }

        return response()->json([
            'orders' => $ticket->orders()->with('user')->get()
        ], 200);
    }
}
