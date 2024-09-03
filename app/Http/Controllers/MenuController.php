<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuPrincipal;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus_principales = MenuPrincipal::all(); // Obtener todos los menús principales

        if ($request->ajax()) {
            $data = Menu::with('menuPrincipal')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id_menu . '" class="edit btn btn-primary btn-sm editRecord">Editar</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id_menu . '" class="btn btn-danger btn-sm deleteRecord">Eliminar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('menus.index', compact('menus_principales')); // Pasar las variables a la vista
    }

    public function store(Request $request)
    {
        Menu::updateOrCreate(
            ['id_menu' => $request->id_menu],
            [
                'id_menu_principal' => $request->id_menu_principal,
                'nombre' => $request->nombre,
                'directorio' => $request->directorio,
                'icono' => $request->icono,
                'imagen' => $request->imagen,
                'color' => $request->color,
                'orden' => $request->orden,
                'estado' => $request->estado,
            ]
        );

        return response()->json(['success' => 'Menú guardado correctamente.']);
    }

    public function edit($id)
    {
        $menu = Menu::find($id);
        return response()->json($menu);
    }

    public function destroy($id)
    {
        Menu::find($id)->delete();

        return response()->json(['success' => 'Menú eliminado correctamente.']);
    }
}
