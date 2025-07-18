<?php

namespace App\Http\Controllers;

use App\Models\Calendar_event;

use Illuminate\Http\Request;

class Calendar_EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'title' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'date|after_or_equal:start',
    ]);

    // Crear el evento
    $event = Calendar_event::create($validatedData);

    // Responder con el evento creado
    return response()->json([
        'message' => 'Evento creado correctamente.',
        'event' => $event,
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Calendar_event::findOrFail($id);
        $event->delete();

       
    }

    public function all(){
        $calendar_events = Calendar_event::all();
        return response()->json($calendar_events);
    }
}
