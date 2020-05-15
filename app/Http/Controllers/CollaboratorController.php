<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Collaborator;

class CollaboratorController extends Controller
{
	public function index(){
		$collaborators = Collaborator::all();

		if(is_object($collaborators) && sizeof($collaborators)!=0){
			$data = array(
				'status'			=> 'success',
				'code'				=> '200',
				'collaborators'		=> $collaborators
			);
		} else {
			$data = array(
				'status'			=> 'error',
				'code'				=> '404',
				'message'			=> 'No existen registros en la base de datos'
			);
		}

		return response()->json($data, $data['code']);
	}

	public function show($id){
		$collaborator = Collaborator::find($id);

		if(is_object($collaborator) && $collaborator!=null){
			$data = array(
				'status'			=> 'success',
				'code'				=> '200',
				'collaborator'		=> $collaborator
			);
		} else {
			$data = array(
				'status'			=> 'error',
				'code'				=> '404',
				'message'			=> 'No existe ningún colaborador registrado con el id '.$id
			);
		}

		return response()->json($data, $data['code']);
	}

	public function getByDocument($document){
		$collaborator = Collaborator::where('documento', $document)
									->get();

		if(is_object($collaborator) && sizeof($collaborator)!=0){
			$data = array(
				'status'			=> 'success',
				'code'				=> '200',
				'collaborator'		=> $collaborator
			);
		} else {
			$data = array(
				'status'			=> 'error',
				'code'				=> '404',
				'message'		=> 'No existe ningún colaborador registrado con el documento '.$document
			);
		}

		return response()->json($data, $data['code']);
	}

	public function store(Request $request){
		// Recoger los datos de la petición
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(is_object($params) && $params != null){
			// Validar los datos
			$validate = \Validator::make($params_array, [
				'nombre'					=> 'required|regex:/^[\pL\s\-]+$/u',
				'tipoDocumento'				=> 'required|numeric',
				'documento'					=> 'required|numeric|unique:collaborator',
				'sexo'						=> 'required|numeric',
				'fechaNacimiento'			=> 'required',
				'edad'						=> 'required|numeric',
				'telefono'					=> 'required|numeric',
				'arl'						=> 'required|numeric',
				'aseguradora'				=> 'required|numeric',
				'direccion'					=> 'required',
				'perfil'					=> 'required|numeric',
				'vinculacion'				=> 'required|numeric',
				'area'						=> 'required|numeric',
				'unidad'					=> 'required|numeric',
				'fechaIngreso'				=> 'required',
				'fechaEgreso'				=> 'required',
				'fechaSintomas'				=> 'required',
				'fiebre'					=> 'required|numeric',
				'malestarGeneral'			=> 'required|numeric',
				'disnea'					=> 'required|numeric',
				'odinia'					=> 'required|numeric',
				'gastrointestinales'		=> 'required|numeric',
				'estornudo'					=> 'required|numeric',
				'mialgias'					=> 'required|numeric',
				'riesgoEndogeno'			=> 'nullable',
				'riesgoExogeno'				=> 'nullable',
				'factoresExposicion'		=> 'nullable',
				'manejo'					=> 'required|numeric',
				'remitido'					=> 'nullable',
				'fechaTomaMuestra'			=> 'required',
				'fechaResultado'			=> 'required',
				'diasTranscurridos'			=> 'required',
				'contagiado'				=> 'required|numeric',
				'nexo'						=> 'required|numeric',
				'primerSeguimiento' 		=> 'required',
				'nombrePrimerSeguimiento'	=> 'required',
				'segundoSeguimiento'		=> 'required',
				'nombreSegundoSeguimiento'	=> 'required',
				'estado'					=> 'required|numeric',
				'trabajo'					=> 'required|numeric',
				'fechaSegundaPrueba'		=> 'nullable',
				'observaciones'				=> 'nullable'
			]);
			if($validate->fails()){
				$data = array(
					'status'	=> 'error',
					'code'		=> 400,
					'message'	=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'	=> $validate->errors()
				);
			} else {
				// Guardar el registro 
				$collaborator = new Collaborator();

				$collaborator->nombre						= $params->nombre;
				$collaborator->tipoDocumento				= $params->tipoDocumento;
				$collaborator->documento					= $params->documento;
				$collaborator->sexo							= $params->sexo;
				$collaborator->fechaNacimiento				= $params->fechaNacimiento;
				$collaborator->edad							= $params->edad;
				$collaborator->telefono						= $params->telefono;
				$collaborator->arl							= $params->arl;
				$collaborator->aseguradora					= $params->aseguradora;
				$collaborator->direccion					= $params->direccion;
				$collaborator->perfil 						= $params->perfil;
				$collaborator->vinculacion 					= $params->vinculacion;
				$collaborator->area 						= $params->area;
				$collaborator->unidad 						= $params->unidad;
				$collaborator->fechaIngreso					= $params->fechaIngreso;
				$collaborator->fechaEgreso					= $params->fechaEgreso;
				$collaborator->fechaSintomas				= $params->fechaSintomas;
				$collaborator->fiebre						= $params->fiebre;
				$collaborator->malestarGeneral				= $params->malestarGeneral;
				$collaborator->disnea						= $params->disnea;
				$collaborator->odinia						= $params->odinia;
				$collaborator->gastrointestinales			= $params->gastrointestinales;
				$collaborator->estornudo					= $params->estornudo;
				$collaborator->mialgias						= $params->mialgias;
				$collaborator->riesgoEndogeno				= $params->riesgoEndogeno;
				$collaborator->riesgoExogeno				= $params->riesgoExogeno;
				$collaborator->factoresExposicion			= $params->factoresExposicion;
				$collaborator->manejo						= $params->manejo;
				$collaborator->remitido						= $params->remitido;
				$collaborator->fechaTomaMuestra				= $params->fechaTomaMuestra;
				$collaborator->fechaResultado				= $params->fechaResultado;
				$collaborator->diasTranscurridos			= $params->diasTranscurridos;
				$collaborator->contagiado					= $params->contagiado;
				$collaborator->nexo							= $params->nexo;
				$collaborator->primerSeguimiento			= $params->primerSeguimiento;
				$collaborator->nombrePrimerSeguimiento		= $params->nombrePrimerSeguimiento;
				$collaborator->segundoSeguimiento			= $params->segundoSeguimiento;
				$collaborator->nombreSegundoSeguimiento		= $params->nombreSegundoSeguimiento;
				$collaborator->estado						= $params->estado;
				$collaborator->trabajo						= $params->trabajo;
				$collaborator->fechaSegundaPrueba			= $params->fechaSegundaPrueba;
				$collaborator->observaciones				= $params->observaciones;

				$collaborator->save();

				$data = array(
					'status'		=> 'success',
					'code'			=> 201,
					'message'		=> 'Se ha realizado correctamente un nuevo registro',
					'collaborator'	=> $collaborator
				);
			}
		} else {
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
			);
		}

		return response()->json($data, $data['code']);
	}

	public function updateRelated($affected, $origin, Request $request){
		$collaborator = Collaborator::find($affected);

		if(is_object($collaborator) && $collaborator != null){

			$origen = Collaborator::find($origin);

			if(is_object($origen) && $origen != null){
				$collaborator = Collaborator::where('id', $affected)
									->update(array('idRelacionado' => $origen->documento));;
				if($collaborator != 0){
					$data = array(
						'status'	=> 'success',
						'code'		=> 201,
						'message'	=> 'Se ha relacionado al usuario '.$affected.' con el origen '.$origin
					);
				} else{
					$data = array(
						'status'	=> 'error',
						'code'		=> 404,
						'message'	=> 'No se ha podido actualizar el registro solicitado '.$id
					);					
				}				
			} else {
				$data = array(
					'status'		=> 'error',
					'code'			=> 404,
					'message'		=> 'El usuario de origen del contagio no existe en la base de datos'
				);
			}
				
		} else {
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'El usuario de afectado del contagio no existe en la base de datos'
			);
		}

		return response()->json($data, $data['code']);
	}

	public function update($id, Request $request){
		// Verificar si existe el registro
		$collaborator = Collaborator::find($id);

		if(is_object($collaborator) && $collaborator != null){
			// Recoger los datos de la petición
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);

			if(is_object($params) && $params != null){
				// Validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'nombre'					=> 'required|regex:/^[\pL\s\-]+$/u',
					'tipoDocumento'				=> 'required|numeric',
					'documento'					=> 'required|numeric|unique:collaborator,documento,'.$id,
					'sexo'						=> 'required|numeric',
					'fechaNacimiento'			=> 'required',
					'edad'						=> 'required|numeric',
					'telefono'					=> 'required|numeric',
					'arl'						=> 'required|numeric',
					'aseguradora'				=> 'required|numeric',
					'direccion'					=> 'required',
					'perfil'					=> 'required|numeric',
					'vinculacion'				=> 'required|numeric',
					'area'						=> 'required|numeric',
					'unidad' 					=> 'required|numeric',
					'fechaIngreso'				=> 'required',
					'fechaEgreso'				=> 'required',
					'fechaSintomas'				=> 'required',
					'fiebre'					=> 'required|numeric',
					'malestarGeneral'			=> 'required|numeric',
					'disnea'					=> 'required|numeric',
					'odinia'					=> 'required|numeric',
					'gastrointestinales'		=> 'required|numeric',
					'estornudo'					=> 'required|numeric',
					'mialgias'					=> 'required|numeric',
					'riesgoEndogeno'			=> 'nullable',
					'riesgoExogeno'				=> 'nullable',
					'factoresExposicion'		=> 'nullable',
					'manejo'					=> 'required|numeric',
					'remitido'					=> 'nullable',
					'fechaTomaMuestra'			=> 'required',
					'fechaResultado'			=> 'required',
					'diasTranscurridos'			=> 'required',
					'contagiado'				=> 'required|numeric',
					'nexo'						=> 'required|numeric',
					'primerSeguimiento' 		=> 'required',
					'nombrePrimerSeguimiento'	=> 'required',
					'segundoSeguimiento'		=> 'required',
					'nombreSegundoSeguimiento'	=> 'required',
					'estado'					=> 'required|numeric',
					'trabajo'					=> 'required|numeric',
					'fechaSegundaPrueba'		=> 'nullable',
					'observaciones'				=> 'nullable',
				]);
				if($validate->fails()){
					$data = array(
						'status'	=> 'error',
						'code'		=> 400,
						'message'	=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
						'errors'	=> $validate->errors()
					);
				} else {
					// Eliminar los datos que no se desean actualizar
					unset($params_array['id']);
					unset($params_array['idRelacionado']);
					unset($params_array['created_at']);
					unset($params_array['updated_at']);

					// Actualizar los datos
					$collaborator = Collaborator::where('id', $id)
									->update($params_array);
					if($collaborator != 0){
						$data = array(
							'status'	=> 'success',
							'code'		=> 201,
							'message'	=> 'Se ha actualizado el registro '.$id.' correctamente',
							'changes'	=> $params_array
						);
					} else{
						$data = array(
							'status'	=> 'error',
							'code'		=> 404,
							'message'	=> 'No se ha podido actualizar el registro solicitado '.$id
						);					
					}
				}
			} else {
				$data = array(
					'status'		=> 'error',
					'code'			=> 400,
					'message'		=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
				);
			}
		} else {
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'El registro que está intentando actualizar no existe en la base de datos'
			);
		}

		return response()->json($data, $data['code']);
	}

	public function destroy($id){
		// Verificar si existe el registro
		$collaborator = Collaborator::find($id);

		if(is_object($collaborator) && $collaborator != 0){
			$collaborator->delete();

			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'message'		=> 'El registro '.$id.' se ha eliminado correctamente'
			);
		} else {
			$data = array(
				'status'		=> 'error',
				'code'			=> 404,
				'message'		=> 'El registro que está intentando eliminar no existe en la base de datos'
			);
		}

		return response()->json($data, $data['code']);
	}
}	
