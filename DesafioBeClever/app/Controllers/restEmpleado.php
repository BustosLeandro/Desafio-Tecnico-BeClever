<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\SucursalModel;
use App\Models\RegistroModel;
use App\Models\TipoRegistroModel;
use App\Models\EmpleadoModel;

class restEmpleado extends ResourceController
{
    protected $modelName = 'App\Models\EmpleadoModel';
    protected $format    = 'json';

    //Services 1 POST
    public function register(){
        $registro = new registroModel();

        //Obtengo las variables pasadas por POST
        $request = \Config\Services::request();
        $idEmpleado = $request->getPost('idEmployee');
        $sucursal = $request->getPost('businessLocation');
        $tipoRegistro = $request->getPost('registerType');

        //Trabajo la variable fecha (De lo contrario la BD no lo renoce)
        $fecha = $request->getPost('date');
        $fecha = date_create($fecha); //Creo un objeto fecha
        $fecha = $fecha->format('Y-m-d'); //Le modifico el formato a yyyy-mm-dd

        $CodigoTipoRegistro = $this->tipoRegistro($tipoRegistro);
        //Valido que el registro ingresado sea valido
        if($CodigoTipoRegistro != 0){

            //Verifico que el usuario ingresado existe
            $empleado = new EmpleadoModel();
            $empleado = $empleado->find($idEmpleado);
            if($empleado != NULL){
                
                //Verifico que el empleado ingresado pertenezca a la sucursal ingresada
                $validacion = $this->validarSede($empleado,$sucursal);
                if($validacion){
                    $datos = array(
                        'CodigoEmpleado'=>$idEmpleado,
                        'Fecha'=>$fecha,
                        'CodigoTipoRegistro'=>$CodigoTipoRegistro
                    );

                    $id = $registro->insert($datos);
                    return $this->respond($registro->find($id));

                }else{
                    //Se que estos mensajes podrian ser una funcion pero la funcion respond no devuelve nada desde una funcion
                    return $this->respond(array(
                        "mensaje" => "El empleado no pertenece a la sucursal especificada",
                        "code" => 404
                    ));
                }
            }else{

                return $this->respond(array(
                    "mensaje" => "El empleado no existe",
                    "code" => 404
                ));
            }
        }else{

            return $this->respond(array(
                "mensaje" => "El tipo de registro es invalido",
                "code" => 404
            ));
        }        
    }

    private function validarSede($empleado,$ubicacion){
        $sucursal = new SucursalModel();
        $sucursales = $sucursal->findAll();
        $arregloSucursales = [];
        $ubicacion = strtolower($ubicacion); //Paso la sucursal a minusculas

        //Acomodo el arreglo [ 'Codigo' => 'Sucursal']
        foreach($sucursales as $sucursal){
            $arregloSucursales[$sucursal['Codigo']] = $sucursal['Sucursal'];
        }

        //Paso la sucursal a la que pertenece el empleado a minusculas
        $sucursalEmpleado = strtolower($arregloSucursales[$empleado['CodigoSucursal']]); 
        
        if($sucursalEmpleado == $ubicacion){
            return true;    
        }else{
            return false;
        }
    }

    private function tipoRegistro($registro){
        $tipos = new TipoRegistroModel();
        $tipos = $tipos->findAll();
        $registro = strtolower($registro); //paso el registro a minusculas
        $codigo = 0; //El predeterminado es uno ya que no puede haber egresos sin ingresos

        foreach($tipos as $tipo){
            $tipo['TipoRegistro'] = strtolower($tipo['TipoRegistro']); //paso el tipo de registro a minusculas
            if($tipo['TipoRegistro'] == $registro){
                $codigo = $tipo['Codigo'];
            }
        }

        return $codigo;
    }

    //Services 2 GET
    public function search($dateFrom, $dateTo, $descriptionFilter, $businessLocation){
        //Creo variables de tipo fecha
        $fechaDesde = date_create($dateFrom);
        $fechaHasta = date_create($dateTo);

        $registros = new registroModel();
        $registros = $registros->buscarConFiltros($descriptionFilter,$businessLocation,$fechaDesde,$fechaHasta);

        if($registros == null){
            return $this->respond(array(
                "mensaje" => "No hay registros almacenados con esas especificaciones",
                "code" => 404
            ));
        }else{
            return $this->respond($registros);
        } 
    }

    //Services 3 GET
    public function average($dateFrom,$dateTo){
        //Creo variables de tipo fecha
        $fechaDesde = date_create($dateFrom);
        $fechaHasta = date_create($dateTo);

        //Valido que la fecha desde sea antes que la fecha hasta
        $intervalo = date_diff($fechaDesde,$fechaHasta);
        if($intervalo->invert > 0){
            //Se que estos mensajes podrian ser una funcion pero la funcion respond no devuelve nada desde una funcion
            return $this->respond(array(
                "mensaje" => "El campo dateTo es menor al campor dateFrom",
                "code" => 404
            ));
        }

        $registros = new registroModel();
        $registros = $registros->buscarXFechas($fechaDesde,$fechaHasta);

        //Separo hombres de mujeres
        $registrosHombres = [];
        $registrosMujeres = [];
        foreach($registros as $registro){
            if($registro['Sexo'] == 1){
                $registrosHombres[] = $registro;
            }else{
                $registrosMujeres[] = $registro;
            }
        }       

        //Obtengo el total de sucursales
        $sucursal = new SucursalModel();
        $sucursales = $sucursal->findAll();
        $promediosHombres = [];
        $promediosMujeres = [];

        //Funcion para calcular la cantidad de dás entre las fechas
        $cantDias = $intervalo->days;
        foreach($sucursales as $sucursal){
            $promediosHombres[] = $this->promedioPorSede($registrosHombres,$sucursal['Sucursal'],$cantDias);
            $promediosMujeres[] = $this->promedioPorSede($registrosMujeres,$sucursal['Sucursal'],$cantDias);
        }

        $promedios = [
            'Hombres' => $promediosHombres,
            'Mujeres' => $promediosMujeres
        ]; 

        return $this->respond($promedios);     
    }

    private function promedioPorSede($registrosEmpleados,$sucursal,$cantDias){
        $cont = 0;
        foreach($registrosEmpleados as $registro){
            if($registro['Sucursal'] == $sucursal){
                $cont++;
            }
        }

        if($cantDias > 0){
            $promedio = $cont / $cantDias;    
        }else{
            $promedio = 0;
        }
        
        $promedioSucursal = [
            'Promedio de registros de '.$sucursal => $promedio 
        ];

        return $promedioSucursal;
    }
}