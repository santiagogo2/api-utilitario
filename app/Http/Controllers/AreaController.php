<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;

class AreaController extends Controller
{
	// =================================================================================
	// =======================Funciones para extraer los datos==========================
	// =================================================================================
	public function index(){
		$areas = Area::withCount('Collaborators')
					 ->orderBy('name')
					 ->get();

		if(is_object($areas) && sizeof($areas)!=0){
			$data = array(
				'status'			=> 'success',
				'code'				=> '200',
				'areas'				=> $areas
			);
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> '400',
				'message'				=> 'No existen registros en la base de datos'
			);
		}

		return response()->json($data, $data['code']);
	}

	public function show($id){
		$area = Area::orderBy('name')
					->find($id);

		if( is_object($area) ){
			$data = array(
				'status'				=> 'success',
				'code'					=> '200',
				'area'					=> $area
			);
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> '400',
				'message'				=> 'No existe ningún area registrada con el id '.$id
			);
		}

		return response()->json($data, $data['code']);
	}

	// =================================================================================
	// =======================Funciones para guardar los datos==========================
	// =================================================================================

	public function store(Request $request){
		// Recoger los datos de la petición
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(is_object($params) && $params != null){
			// Validar los datos
			$validate = \Validator::make($params_array, [
				'name'					=> 'required|regex:/^[\pL\s\-]+$/u|unique:areas',
			]);
			if($validate->fails()){
				$data = array(
					'status'			=> 'error',
					'code'				=> 400,
					'message'			=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'			=> $validate->errors()
				);
			} else {
				// Guardar el registro 
				$area = new Area();

				$area->name				= strtoupper($params->name);

				$area->save();

				$data = array(
					'status'			=> 'success',
					'code'				=> 201,
					'message'			=> 'Se ha realizado correctamente un nuevo registro: '.$area->name,
					'area'				=> $area
				);
			}
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> 400,
				'message'				=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
			);
		}

		return response()->json($data, $data['code']);
	}

	// =================================================================================
	// ======================Funciones para actualizar los datos========================
	// =================================================================================
	public function update($id, Request $request){
		// Verificar si existe el registro
		$area = Area::find($id);

		if( is_object($area) ){
			// Recoger los datos de la petición
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);

			if( is_object($params) ){
				// Validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'name'				=> 'required|regex:/^[\pL\s\-]+$/u|unique:areas,name,'.$id,
				]);
				if($validate->fails()){
					$data = array(
						'status'		=> 'error',
						'code'			=> 400,
						'message'		=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
						'errors'		=> $validate->errors()
					);
				} else {
					// Eliminar los datos que no se desean actualizar
					unset($params_array['id']);
					unset($params_array['created_at']);
					unset($params_array['updated_at']);

					// Uppercase
					$params_array['name'] = strtoupper($params_array['name']);

					// Actualizar los datos
					$area = Area::where('id', $id)
								->update($params_array);
					if($area != 0){
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

	// =================================================================================
	// =======================Funciones para eliminar los datos=========================
	// =================================================================================
	public function destroy($id){
		// Verificar si existe el registro
		$area = Area::find($id);

		if( is_object($area) ){
			$name = $area->name;

			$area->delete();

			$data = array(
				'status'		=> 'success',
				'code'			=> 200,
				'message'		=> 'El registro '.$name.' se ha eliminado correctamente'
			);
		} else {
			$data = array(
				'status'		=> 'error',
				'code'			=> 400,
				'message'		=> 'El registro que está intentando eliminar no existe en la base de datos'
			);
		}

		return response()->json($data, $data['code']);
	}
}