<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events, 200);
    }
   
    public function show($id)
    {
        $event = Event::find($id);
        return response()->json($event, 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'artist' => 'required|string|max:255',
            'event' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'date' => 'required|date',
            'poster' => 'required|url',
            'info' => 'required|string|max:1000',
            'policies' => 'nullable|string|max:1000',
            'spotify_iframe' => 'nullable|string|max:1000',
			'venue_iframe'=> 'nullable|string|max:1000',
          
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

   
  
    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'artist' => 'max:255',
            'event' => 'max:255',
            'venue' => 'max:255',
            'city' => 'max:255',
            'date' => 'max:255',
            'poster' => 'max:255',
            'info' => 'max:1000',
            'policies' => 'max:1000',
            'spotify_iframe' => 'nullable|string|max:1000',
           
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $event->update($request->all());
        return response()->json($event, 200);
    }
    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();
        return response()->json(['message' => 'Event deleted successfully'], 200);
    }

    public function bulkStore(Request $request)
    {
        \Log::info('Datos recibidos en bulkStore:', $request->all());
        $validator = Validator::make($request->all(), [
            'events' => 'required|array|min:1',
            'events.*.artist' => 'required|string|max:255',
            'events.*.event' => 'required|string|max:255',
            'events.*.venue' => 'required|string|max:255',
            'events.*.city' => 'required|string|max:255',
            'events.*.date' => 'required|date',
            'events.*.poster' => 'required|url',
            'events.*.info' => 'required|string|max:1000',
            'events.*.policies' => 'nullable|string|max:1000',
            'events.*.spotify_iframe' => 'nullable|string|max:1000',
			 'events.*.venue_iframe' => 'nullable|string|max:1000'
            
        
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $created = [];
        foreach ($request->events as $eventData) {
            $created[] = Event::create($eventData);
        }
        return response()->json($created, 201);
    }
}
