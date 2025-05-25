<?php

namespace App\Http\Controllers;
use App\Models\Action;
use App\Events\ActionCreated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function index()
    {
        $actions = Action::all();
        return response()->json($actions, 200);
    }
    public function webIndex()
    {
        $actions = Action::all();
        return view('actions.index', compact('actions'));
    }

    public function show($id)
    {
        $action = Action::find($id);
        if (!$action) {
            return response()->json(['message' => 'Action not found'], 404);
        }
        return response()->json($action, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $action = Action::create($validator->validated());
        return response()->json($action, 201);
    }

    

    public function update(Request $request, $id)
    {
        $action = Action::find($id);
        if (!$action) {
            return response()->json(['message' => 'Action not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'string|max:255',
            'description' => 'string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $action->update($request->all());
        return response()->json($action, 200);
    }

    public function destroy($id)
    {
        $action = Action::find($id);
        if (!$action) {
            return response()->json(['message' => 'Action not found'], 404);
        }

        $action->delete();
        return response()->json(['message' => 'Action deleted successfully'], 200);
    }

}
