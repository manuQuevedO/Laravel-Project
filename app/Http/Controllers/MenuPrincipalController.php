<?php

namespace App\Http\Controllers;

use App\Models\MenuPrincipal;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MenuPrincipalController extends Controller
{
    /**
     * Mostrar una lista de los menús principales.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modulos = Modulo::all(); // Obtener todos los módulos

        if ($request->ajax()) {
            $data = MenuPrincipal::with('modulo')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id_menu_principal . '" class="edit btn btn-primary btn-sm editRecord">Editar</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id_menu_principal . '" class="btn btn-danger btn-sm deleteRecord">Eliminar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('menus_principales.index', compact('modulos')); // Pasar la variable a la vista
    }

    /**
     * Mostrar el formulario para crear un nuevo menú principal.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $modulos = Modulo::all(); // Obtén todos los módulos disponibles
        // return view('menus_principales.create', compact('modulos'));
    }

    public function store(Request $request)
    {
        MenuPrincipal::updateOrCreate(
            ['id_menu_principal' => $request->id_menu_principal],
            [
                'nombre' => $request->nombre,
                'icono' => $request->icono,
                'orden' => $request->orden,
                'estado' => $request->estado,
                'id_modulo' => $request->id_modulo,
            ]
        );

        return response()->json(['success' => 'Menú Principal guardado correctamente.']);
    }
    /**
     * Mostrar el formulario para editar un menú principal existente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $menuPrincipal = MenuPrincipal::findOrFail($id);
        // $modulos = Modulo::all(); // Obtén todos los módulos disponibles
        // return view('menus_principales.edit', compact('menuPrincipal', 'modulos'));
        $menuPrincipal = MenuPrincipal::find($id);
        return response()->json($menuPrincipal);
    }

    /**
     * Actualizar un menú principal existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_modulo' => 'required|integer|exists:modulos,id_modulo',
            'nombre' => 'required|string|max:250',
            'icono' => 'nullable|string|max:70',
            'orden' => 'nullable|integer',
            'estado' => 'required|string|max:1',
        ]);

        $menuPrincipal = MenuPrincipal::findOrFail($id);
        $menuPrincipal->update($request->all());

        return redirect()->route('menus_principales.index')
            ->with('success', 'Menú Principal actualizado exitosamente.');
    }

    /**
     * Eliminar un menú principal de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $menuPrincipal = MenuPrincipal::findOrFail($id);
        // $menuPrincipal->delete();

        // return redirect()->route('menus_principales.index')
        //     ->with('success', 'Menú Principal eliminado exitosamente.');
        MenuPrincipal::find($id)->delete();

        return response()->json(['success' => 'Menú Principal eliminado correctamente.']);
    }
}
