<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Disponibilidad;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CitaController extends Controller
{
    public function index(){
        $citas = Cita::with('disponibilidad')
        ->with('servicio')
        ->with('user')
        ->where('estado','pendiente')
        ->get();
        return response()->json($citas);
    }

    public function store(Request $request){
        $request->validate([
            'dolencia' => 'nullable|string',
            'disponibilidad' => 'nullable|array',
            'servicio'=> 'required|exists:servicios,id',


        ]);
        $input = $request->all();

        try{
            $cita = new Cita($input);
            $servicio= Servicio::findOrFail($request->input("servicio"));
            $cita->servicio()->associate($servicio);
            $user= Auth::user();
            $cita->user()->associate($user);
            $cita-> estado='pendiente';
            if($cita->save()){
                $this->disponibilidad($input["disponibilidad"],$cita->id );
            }
            return $cita;



        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }


    }


    public function disponibilidad(array $disponibilidad , $cita_id = null ){
            foreach ($disponibilidad as $disponibilidad) {
                $disponibilidad=new disponibilidad($disponibilidad);
                $disponibilidad->cita_id  = $cita_id;
                $disponibilidad->save();
            }
    }
}
