<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        return view('login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Correo o contraseña incorrectos');
    }

    Auth::login($user);

    //return redirect()->route('login.success')->with('success', '¡Inicio de sesión exitoso!');
     return redirect()->route('login.success')->with('success', 'Usuario logueado exitosamente');
    }

    /**
     * Display the specified resource.
     */
   public function show($name, $lastName, $email, $password, $carne, $major_id)
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
