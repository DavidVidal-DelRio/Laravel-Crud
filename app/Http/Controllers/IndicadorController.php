<?php

namespace App\Http\Controllers;
use App\Models\Indicador;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;

class IndicadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Indicador::select('id','nombreIndicador','codigoIndicador','unidadMedidaIndicador','valorIndicador','fechaIndicador')->where('codigoIndicador', '=', 'UF')->orderBy('fechaIndicador','asc')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"> <i class="bi bi-pencil-square"></i>Editar</button>';
                    $button .= '   <button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm"> <i class="bi bi-backspace-reverse-fill"></i> Eliminar</button>';
                    return $button;
                })
                ->make(true);
        }

        return view('indicadors');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function accion(Request $request)
    {
        if ($request->isMethod('post')){
            $desde = $request->input("dateDesde");
            $hasta = $request->input("dateHasta");
        }


            $datos = Indicador::select('valorIndicador','fechaIndicador')->where('codigoIndicador', '=', 'UF' )->whereBetween('fechaIndicador', [$desde, $hasta])->orderBy('fechaIndicador','asc')->get();

            $puntos = [];
            foreach($datos as $dato){
                $puntos[] = ['name' => $dato['fechaIndicador'], 'y'=> floatval($dato['valorIndicador'])];
            }

        $fechas = $desde.' / '.$hasta;
            return view('welcome',["data" => json_encode($puntos)],["fechas" => json_encode($fechas)]);
    }

    public function indexGraf()
    {
        $datos = Indicador::select('valorIndicador','fechaIndicador')->where('codigoIndicador', '=', 'UF')->orderBy('fechaIndicador','asc')->get();

        $puntos = [];
        foreach($datos as $dato){
            $puntos[] = ['name' => $dato['fechaIndicador'], 'y'=> floatval($dato['valorIndicador'])];
        }
        $fechas = "";
        return view('welcome',["data" => json_encode($puntos)],["fechas" => json_encode($fechas)]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form_data = array(
            'nombreIndicador'    =>  $request->nombre,
           'codigoIndicador'     =>  $request->codigo,
           'unidadMedidaIndicador' =>  $request->unidad,
           'valorIndicador'     =>  $request->valor,
           'fechaIndicador'     =>  $request->fecha,
       );

       Indicador::create($form_data);

       return response()->json(['success' => 'Registro agregado correctamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Indicador::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $form_data = array(
            'nombreIndicador'    =>  $request->nombre,
            'codigoIndicador'     =>  $request->codigo,
            'unidadMedidaIndicador'  =>  $request->unidad,
            'valorIndicador'     =>  $request->valor,
            'fechaIndicador'     =>  $request->fecha,
        );

        Indicador::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Registro actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Indicador::findOrFail($id);
        $data->delete();
    }
}
