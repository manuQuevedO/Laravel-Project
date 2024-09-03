<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Universidad;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $universidades = Universidad::all(); // Obtener todas las universidades

        if ($request->ajax()) {
            $data = Area::with('universidad')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id_area . '" class="edit btn btn-primary btn-sm editRecord">Editar</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id_area . '" class="btn btn-danger btn-sm deleteRecord">Eliminar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('areas.index', compact('universidades')); // Pasar la variable a la vista
    }


    public function store(Request $request)
    {
        Area::updateOrCreate(
            ['id_area' => $request->id_area],
            [
                'nombre' => $request->nombre,
                'nombre_abreviado' => $request->nombre_abreviado,
                'estado' => $request->estado,
                'id_universidad' => $request->id_universidad,
            ]
        );

        return response()->json(['success' => 'Área guardada correctamente.']);
    }

    public function edit($id)
    {
        $area = Area::find($id);
        return response()->json($area);
    }

    public function destroy($id)
    {
        Area::find($id)->delete();

        return response()->json(['success' => 'Área eliminada correctamente.']);
    }
}
