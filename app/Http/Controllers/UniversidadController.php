<?php

namespace App\Http\Controllers;

use App\Models\Universidad;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UniversidadController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Universidad::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id_universidad . '" class="edit btn btn-primary btn-sm editRecord">Editar</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id_universidad . '" class="btn btn-danger btn-sm deleteRecord">Eliminar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('universidades.index');
    }

    public function store(Request $request)
    {
        Universidad::updateOrCreate(
            ['id_universidad' => $request->id_universidad],
            [
                'nombre' => $request->nombre,
                'nombre_abreviado' => $request->nombre_abreviado,
                'inicial' => $request->inicial,
                'estado' => $request->estado
            ]
        );

        return response()->json(['success' => 'Universidad guardada correctamente.']);
    }

    public function edit($id)
    {
        $universidad = Universidad::find($id);
        return response()->json($universidad);
    }

    public function destroy($id)
    {
        Universidad::find($id)->delete();

        return response()->json(['success' => 'Universidad eliminada correctamente.']);
    }
}
