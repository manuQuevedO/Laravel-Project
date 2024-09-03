<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Universidad;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConfiguracionController extends Controller
{
    public function index(Request $request)
    {
        $universidades = Universidad::all(); // Obtener todas las universidades

        if ($request->ajax()) {
            $data = Configuracion::with('universidad')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id_configuracion . '" class="edit btn btn-primary btn-sm editRecord">Editar</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id_configuracion . '" class="btn btn-danger btn-sm deleteRecord">Eliminar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('configuraciones.index', compact('universidades')); // Pasar la variable a la vista
    }

    public function store(Request $request)
    {
        Configuracion::updateOrCreate(
            ['id_configuracion' => $request->id_configuracion],
            [
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
                'estado' => $request->estado,
                'id_universidad' => $request->id_universidad,
            ]
        );

        return response()->json(['success' => 'Configuración guardada correctamente.']);
    }

    public function edit($id)
    {
        $configuracion = Configuracion::find($id);
        return response()->json($configuracion);
    }

    public function destroy($id)
    {
        Configuracion::find($id)->delete();

        return response()->json(['success' => 'Configuración eliminada correctamente.']);
    }
}
