<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RolController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Rol::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id_rol . '" class="edit btn btn-primary btn-sm editRecord">Editar</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id_rol . '" class="btn btn-danger btn-sm deleteRecord">Eliminar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('roles.index');
    }

    public function store(Request $request)
    {
        Rol::updateOrCreate(
            ['id_rol' => $request->id_rol],
            [
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'estado' => $request->estado
            ]
        );

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $rol = Rol::find($id);
        return response()->json($rol);
    }

    public function destroy($id)
    {
        Rol::find($id)->delete();

        return response()->json(['success' => 'Rol eliminado correctamente.']);
    }
}
