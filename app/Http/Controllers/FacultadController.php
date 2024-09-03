<?php

namespace App\Http\Controllers;

use App\Models\Facultad;
use App\Models\Area;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FacultadController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::all(); // Obtener todas las áreas

        if ($request->ajax()) {
            $data = Facultad::with('area')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id_facultad . '" class="edit btn btn-primary btn-sm editRecord">Editar</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id_facultad . '" class="btn btn-danger btn-sm deleteRecord">Eliminar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('facultades.index', compact('areas')); // Pasar las áreas a la vista
    }

    public function store(Request $request)
    {
        Facultad::updateOrCreate(
            ['id_facultad' => $request->id_facultad],
            [
                'nombre' => $request->nombre,
                'nombre_abreviado' => $request->nombre_abreviado,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'telefono_interno' => $request->telefono_interno,
                'fax' => $request->fax,
                'email' => $request->email,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'fecha_creacion' => $request->fecha_creacion,
                'escudo' => $request->escudo,
                'imagen' => $request->imagen,
                'estado_virtual' => $request->estado_virtual,
                'estado' => $request->estado,
                'id_area' => $request->id_area,  // Relación con la tabla áreas
            ]
        );

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $facultad = Facultad::find($id);
        return response()->json($facultad);
    }

    public function destroy($id)
    {
        Facultad::find($id)->delete();

        return response()->json(['success' => 'Facultad eliminada correctamente.']);
    }
}
