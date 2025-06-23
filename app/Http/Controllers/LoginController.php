<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
class LoginController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
   /* public function show($name, $lastName, $email, $password, $carne, $major_id)
    {

       // $nuevoUsuario = new User();
        $nuevoUsuario = \App\Models\User::create([
            'name' => $name,
            'last_name' => $lastName,
            'email' => $email,
            'password' => bcrypt($password),
            'carne' => $carne,
            'major_id' => $major_id
        ]);

        return "Usuario $name creado con exito";
    }*/

    public function show($major_name)
    {
        $newMajor = \App\Models\Major::create([
            'major_name' => $major_name
        ]);

        return  $major_name ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
      
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
        //
    }
}
