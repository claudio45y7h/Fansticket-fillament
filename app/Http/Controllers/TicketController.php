<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\TicketCategory;

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
            'category_id' => 'required|integer|exists:categories,id',
            'section' => 'nullable|string',
            'row' => 'nullable|string',
            'seat' => 'nullable|string',
            'info' => 'required|string',
            'stock' => 'required|integer|min:0',
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
            'category_id' => 'required|integer|exists:categories,id','section' => 'nullable|string',
            'row' => 'nullable|string',
            'seat' => 'nullable|string',
            'info' => 'string',
            'stock' => 'integer|min:0',
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
        try {
            $validator = Validator::make($request->all(), [
                'tickets' => 'required|array|min:1',
                'tickets.*.event_id' => 'required|integer|exists:events,id',
                'tickets.*.category_id' => 'required|integer|exists:ticket_categories,id',
                'tickets.*.section' => 'nullable|string|max:50',
                'tickets.*.row' => 'nullable|string|max:10',
                'tickets.*.seat' => 'nullable|string|max:10',
                'tickets.*.info' => 'required|string|max:255',
                'tickets.*.stock' => 'required|integer|min:1',
                'tickets.*.id' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $tickets = collect($request->tickets);
            
            // Agrupar tickets por evento para verificar disponibilidad
            $eventTickets = $tickets->groupBy('event_id')->map->sum('stock');
            
            // Verificar disponibilidad para cada evento
            foreach ($eventTickets as $eventId => $totalTickets) {
                $event = Event::find($eventId);
                if (!$event) {
                    throw new \Exception("Evento con ID {$eventId} no encontrado");
                }
                if ($event->available_tickets < $totalTickets) {
                    throw new \Exception("No hay suficientes tickets disponibles para el evento {$event->name}");
                }
            }

            // Verificar las categorías
            $categoryIds = $tickets->pluck('category_id')->unique();
            $categories = TicketCategory::whereIn('id', $categoryIds)
                                     ->where('is_active', true)
                                     ->get()
                                     ->keyBy('id');

            foreach ($categoryIds as $categoryId) {
                if (!isset($categories[$categoryId])) {
                    throw new \Exception("La categoría de ticket {$categoryId} no está activa o no existe");
                }
            }

            $created = [];
            foreach ($request->tickets as $ticketData) {
                // Obtener el precio de la categoría
                $category = $categories[$ticketData['category_id']];
                
                // Crear el ticket con los datos de la categoría
                $ticket = Ticket::create([
                    'event_id' => $ticketData['event_id'],
                    'category_id' => $ticketData['category_id'],
                    'section' => $ticketData['section'] ?? null,
                    'row' => $ticketData['row'] ?? null,
                    'seat' => $ticketData['seat'] ?? null,
                    'info' => $ticketData['info'],
                    'stock' => $ticketData['stock'],
                    'price' => $category->price,
                    'type' => $category->type,
                    'id' => $ticketData['id'] ?? null,
                ]);

                // Actualizar el stock disponible del evento
                $event = Event::find($ticketData['event_id']);
                $event->decrement('available_tickets', $ticketData['stock']);

                $created[] = $ticket;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tickets creados exitosamente',
                'data' => collect($created)->load(['event', 'category'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear los tickets',
                'error' => $e->getMessage()
            ], 500);
        }
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

