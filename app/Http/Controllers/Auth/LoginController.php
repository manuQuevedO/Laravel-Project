<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Menu\MenuManager;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Manejar la autenticación del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Intentar autenticar al usuario
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $menuManager = new MenuManager();
            $menuData = $menuManager->getMenuData(Auth::id());

            // dd($menuData->toArray()); // Esto mostrará el contenido de la colección como un array


            session(['menu' => $menuManager->getMenu($menuData)]);

            return redirect()->intended('/');
        }





        // Autenticación fallida
        return back()->withErrors([
            'username' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Cerrar sesión del usuario.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Limpiar el menú de la sesión
        session()->forget('menu');
        Auth::logout();
        return redirect('/login');
    }
}
