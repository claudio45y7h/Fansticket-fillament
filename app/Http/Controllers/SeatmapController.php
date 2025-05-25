<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Seatmap;

class SeatmapController extends Controller
{
    public function index()
    {
        $seatmaps = Seatmap::all();
        return response()->json($seatmaps);
    }

    public function show($id)
    {
        $seatmap = Seatmap::find($id);
        return response()->json($seatmap);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|integer|exists:events,id',
                'viewbox' => 'required|string',
                'class' => 'required|string',
                'background_image' => 'required|string',
                'sections' => 'required|array',
                'polygons' => 'required|array',
                
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // No necesitamos convertir a JSON ya que está definido en los casts del modelo
            $seatmap = new Seatmap();
            foreach ($data as $key => $value) {
                if (in_array($key, $seatmap->getFillable())) {
                    $seatmap->{$key} = $value;
                }
            }
            
            $seatmap->save();
            
            return response()->json([
                'message' => 'Mapa de asientos guardado exitosamente',
                'data' => $seatmap
            ], 201);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al guardar el mapa de asientos: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error al procesar el mapa de asientos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSeatMapByEventId($id)
    {
        $seatmap = Seatmap::where('event_id', $id)->first();
        
        if (!$seatmap) {
            return response()->json(['message' => 'No se encontró mapa de asientos para este evento'], 404);
        }
        
        return response()->json($seatmap);
    }
}
