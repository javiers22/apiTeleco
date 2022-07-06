<?php
/**
 * Created by PhpStorm.
 * User: javier
 * Date: 3/08/2018
 * Time: 3:32 PM
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
//require 'vendor/autoload.php';

//use PHPMailerAutoload; 
//use PHPMailer;

class AppController extends Controller
{
    public function facultades(Request $request)
    {
        if($request->input("llave")=="usantotomas2022*")
        {
            $facultades= DB::select( DB::raw("SELECT cod_unidad,nom_unidad
            FROM src_uni_academica a
            WHERE a.ID_CIRCULO=".$request->input("id_circulo")." and a.cod_modalidad=1"));           

            return response()->json([
                'facultades' => $facultades,        
            ]);
        }
        else
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function programas(Request $request)
    {
        if($request->input("llave")=="usantotomas2022*")
        {
            $programas= DB::select( DB::raw("SELECT cod_unidad,nom_unidad
            FROM src_uni_academica a
            WHERE a.cod_anterior='".$request->input("cod_unidad")."' and a.cod_modalidad<>'1'"));           

            return response()->json([
                'programas' => $programas,        
            ]);
        }
        else
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function estudiantes(Request $request)
    {
        if($request->input("llave")=="usantotomas2022*")
        {
            $estudiantes= DB::select( DB::raw("SELECT a.NUM_IDENTIFICACION,a.NOM_LARGO,d.cod_unidad,d.NOM_UNIDAD,c.COD_PERIODO 
            FROM bas_tercero a,src_alum_programa b,src_alum_periodo c,SRC_UNI_ACADEMICA d
            WHERE a.id_tercero=b.id_tercero
            AND b.EST_ALUMNO in(1,7)
            AND c.ID_ALUM_PROGRAMA=b.ID_ALUM_PROGRAMA
            AND c.COD_PERIODO=(select max(cod_periodo) from src_act_academica where val_actividad=27 and cod_unidad='11001')
            AND c.EST_MAT_FIN=1
            AND c.EST_MAT_ACA=1
            AND b.COD_UNIDAD=d.COD_UNIDAD
            AND d.cod_unidad='".$request->input("cod_unidad")."'"));           

            return response()->json([
                'estudiantes' => $estudiantes,        
            ]);
        }
        else
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}