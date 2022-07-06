<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    
    public function login(Request $request)
    {
        $password = md5($request->input("password"));
        $user=User::Where("num_identificacion","=",$request->input("num_identificacion"))->where("password","=",$password)->first();
        //var_dump($user);
        try{
            if (! $token = auth()->login($user)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }catch(JWTException $e){
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function prueba($id){
        return response()->json(['status' => $id], 200);
    }

    public function vinculacion(){
        $DatosEstudiante = false;
        $DatosDocente = false;
        //Traer roles
        $listaRoles = DB::select( DB::raw("select a.COD_TABLA, b.NOM_TABLA from BAS_TIP_TERCERO a 
        JOIN (SELECT * FROM src_generica WHERE TIP_TABLA = 'TIPTER') b ON (a.COD_TABLA = b.COD_TABLA) 
        WHERE a.id_tercero = ".auth()->user()->id_tercero.""));
        //var_dump($listaRoles);die();
        for($i = 0; $i < count($listaRoles); $i++) {
            $DatosEstudiante="";
            $DatosDocente="";
            $DatosDecano="";
            $DatosSecretaria="";
            switch($listaRoles[$i]->cod_tabla){
                case 1:
                    //Si es estudiante
                    $DatosEstudiante = DB::select( DB::raw("select c.id_sede, c.nom_sede, d.id_circulo, d.nom_circulo, b.cod_unidad, b.nom_unidad, e.cod_periodo
                    from src_alum_programa a JOIN src_uni_academica b ON (a.cod_unidad = b.cod_unidad) 
                    JOIN src_cir_academico d ON (b.id_circulo = d.id_circulo)
                    JOIN (select id_alum_programa, max(cod_periodo) as cod_periodo from src_alum_periodo group by id_alum_programa) e
                    ON (a.id_alum_programa = e.id_alum_programa)
                    JOIN src_sede c ON (b.id_sede = c.id_sede)
                    where a.id_tercero = ".auth()->user()->id_tercero.""));
                    break;
                case 3:
                    //Si es docente
                    $DatosDocente = DB::select( DB::raw("select d.id_sede,d.nom_sede,e.id_circulo,e.nom_circulo,c.cod_unidad,c.nom_unidad,b.cod_periodo 
                    from bas_tercero a,src_vis_nom_carga b,src_uni_academica c,src_sede d,src_cir_academico e
                    where a.id_tercero=b.id_tercero
                    and b.cod_unidad=c.cod_unidad
                    and c.id_sede=d.id_sede
                    and c.id_circulo=e.id_circulo
                    and b.cod_periodo=(select max(cod_periodo) from src_act_academica where val_actividad=27 and cod_unidad='11001')
                    and a.id_tercero = ".auth()->user()->id_tercero."
                    UNION
                    select d.id_sede,d.nom_sede,e.id_circulo,e.nom_circulo,c.cod_unidad,c.nom_unidad,b.cod_per_vigencia cod_periodo
                    from bas_tercero a,BAS_VIS_TER_ACTIVIDAD_WEB b,src_uni_academica c,src_sede d,src_cir_academico e
                    where a.id_tercero=b.id_tercero
                    and b.cod_unidad=c.cod_unidad
                    and c.id_sede=d.id_sede
                    and c.id_circulo=e.id_circulo
                    and b.cod_per_vigencia=(select max(cod_periodo) from src_act_academica where val_actividad=27 and cod_unidad='11001')
                    and a.id_tercero = ".auth()->user()->id_tercero.
                    ""));
                    break;               
                case 19:
                   //Si es decano
                    $DatosDecano=DB::select(DB::raw("SELECT c.id_sede,c.nom_sede,d.id_circulo,d.nom_circulo,b.cod_unidad,b.nom_unidad,(select max(cod_periodo) from src_act_academica where val_actividad=27 and cod_unidad='11001')cod_periodo 
                    FROM bas_tercero a,src_uni_academica b,src_sede c,src_cir_academico d 
                    WHERE a.id_tercero=b.id_tercero
                    AND b.id_sede=c.ID_SEDE
                    AND b.ID_CIRCULO=d.id_circulo
                    AND a.id_tercero=".auth()->user()->id_tercero.""));
                    break;
                case 18:
                    //Si es secretaria div
                    $DatosSecretaria=DB::select(DB::raw("SELECT g.id_sede,g.nom_sede,f.id_circulo,f.nom_circulo,e.cod_unidad,e.nom_unidad,(select max(cod_periodo) from src_act_academica where val_actividad=27 and cod_unidad='11001')cod_periodo
                    FROM bas_tercero a, bas_tip_tercero b,SRC_TER_SEDE c,SRC_TER_UNIDAD d,src_uni_academica e,src_cir_academico f,src_sede g
                    WHERE a.id_tercero=b.id_tercero
                    AND b.COD_TABLA=18
                    AND a.id_tercero=c.id_tercero
                    AND a.id_tercero=d.id_tercero
                    AND d.COD_UNIDAD=e.cod_unidad
                    AND e.ID_CIRCULO=f.ID_CIRCULO
                    AND c.id_sede=g.id_sede
                    AND a.id_tercero=".auth()->user()->id_tercero.""));
                
            }
        }
        
        return response()->json([
            'estudiante' => $DatosEstudiante,
            'docente' => $DatosDocente,
            'decano' => $DatosDecano,
            'secretaria' => $DatosSecretaria,
        ]);
        


        //echo auth()->user()->id_tercero;

    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
