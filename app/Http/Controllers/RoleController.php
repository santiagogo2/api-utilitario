<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class RoleController extends Controller
{
	// =================================================================================
	// =======================Funciones para extraer los datos==========================
	// =================================================================================
	public function index(){
		// Buscar todos los roles en la base de datos
		$roles = Role::all();

		if( is_object($roles) && sizeof($roles) != 0 ){
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'roles'				=> $roles
			);
		} else {
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'No existen roles almacenados en la base de datos'
			);
		}

		// Devolver la respuesta
		return response()->json($data, $data['code']);
	}

	public function show($id){
		// Buscar el role por el id ingresado por la url
		$role = Role::find($id);

		if( is_object($role) ){
			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'role'				=> $role
			);
		} else {
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'No se encontraron roles con el id: '.$id
			);
		}

		// Devolver la respuesta
		return response()->json($data, $data['code']);
	}

	// =================================================================================
	// =======================Funciones para guardar los datos==========================
	// =================================================================================
	public function store( Request $request ){
		// Recoger los datos de la petición
		$json = $request->input( 'json', null );
		$params = json_decode( $json );
		$params_array = json_decode( $json, true );

		// Verificar si se ingresaron bien los datos
		if( is_object($params) ){
			// Validar los datos ingresados
			$validate = \Validator::make( $params_array, [
				'name'				=> 'required|unique:roles',
				'description'		=> 'nullable'
			]);

			if( $validate->fails() ){
				$data = array(
					'status'		=> 'error',
					'code'			=> 400,
					'message'		=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'		=> $validate->errors()
				);
			} else {
				// Guardar el registro en la base de datos
				$role = new Role();

				$role->name 		= strtoupper($params->name);
				$role->description 	= $params->description;

				$role->save();

				$data = array(
					'status'		=> 'success',
					'code'			=> 201,
					'message'		=> 'Se ha realizado correctamente un nuevo registro',
					'role'			=> $role
				);
			}
		} else {
			$data = array(
				'status'			=> 'error',
				'code'				=> 500,
				'message'			=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
			);
		}

		// Devolver la respuesta
		return response()->json( $data, $data['code'] );
	}

	// =================================================================================
	// ======================Funciones para actualizar los datos========================
	// =================================================================================
	public function update( $id, Request $request ){
		$role = Role::find($id);

		if( is_object($role) ){
			// Recoger los datos de la petición
			$json = $request->input( 'json', null );
			$params = json_decode( $json );
			$params_array = json_decode( $json, true );

			// Verificar si se ingresaron los datos
			if( is_object($params) ){
				// Validar los datos ingresados
				$validate = \Validator::make( $params_array, [
					'name'				=> 'required|unique:roles,name,'.$id,
					'description'		=> 'nullable'
				]);

				if( $validate->fails() ){
					$data = array(
						'status'		=> 'error',
						'code'			=> 400,
						'message'		=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
						'errors'		=> $validate->errors()
					);
				} else {
					// Eliminar los datos que no se desean actualizar
					unset( $params_array['id'] );
					unset( $params_array['updated_at'] );
					unset( $params_array['created_at'] );

					$params_array['name'] = strtoupper($params_array['name']);

					// Actualizar los datos
					$role = Role::where( 'id', $id )
								->update( $params_array );
					if( $role != 0 ){
						$data = array(
							'status'	=> 'success',
							'code'		=> 201,
							'message'	=> 'Se ha actualizado el registro '.$id.' correctamente',
							'changes'	=> $params_array
						);
					} else {
						$data = array(
							'status'	=> 'error',
							'code'		=> 400,
							'message'	=> 'No se ha podido actualizar el registro solicitado '.$id
						);
					}
				}
			} else {
				$data = array(
					'status'			=> 'error',
					'code'				=> 500,
					'message'			=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
				);
			}
		} else {
			$data = array(
				'status'				=> 'error',
				'code'					=> 400,
				'message'				=> 'El registro que está intentando actualizar no existe en la base de datos'
			);
		}

		// Devolver la respuesta
		return response()->json( $data, $data['code'] );
	}

	// =================================================================================
	// =======================Funciones para eliminar los datos=========================
	// =================================================================================
	public function destroy( $id ){
		// Verificar si existe el registro que se desea eliminar
		$role = Role::find( $id );

		if( is_object($role) ){
			$role_name = $role->name;

			$role->delete();

			$data = array(
				'status'			=> 'success',
				'code'				=> 200,
				'message'			=> 'El role '.$role_name.' se ha eliminado correctamente'
			);
		} else {
			$data = array(
				'status'			=> 'error',
				'code'				=> 400,
				'message'			=> 'El registro que está intentando eliminar no existe en la base de datos'
			);
		}

		// Devolver la respuesta
		return response()->json( $data, $data['code'] );
	}
}