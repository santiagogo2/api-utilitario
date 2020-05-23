<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contractor;

class ContractorController extends Controller
{
	// =================================================================================
	// =======================Funciones para extraer los datos==========================
	// =================================================================================
	public function index(){
		// Buscar todos los contratistas en la base de datos
		$contractors = Contractor::all();

		if( is_object($contractors) && sizeof($contractors) != 0 ){
			$data = array(
				'status'					=> 'success',
				'code'						=> 200,
				'contractors'				=> $contractors
			);
		} else {
			$data = array(
				'status'					=> 'error',
				'code'						=> 400,
				'message'					=> 'No se han encontrado contratistas en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json( $data, $data['code'] );
	}

	public function show( $id ){
		// Buscar el contratista por el id ingresado en la url
		$contractor = Contractor::find($id);

		if( is_object($contractor) ){
			$data = array(
				'status'					=> 'success',
				'code'						=> 200,
				'contractor'				=> $contractor
			);
		} else {
			$data = array(
				'status'					=> 'error',
				'code'						=> 400,
				'message'					=> 'No se han encontrado ningún contratista con el id: '. $id
			);
		}

		// Devolver respuesta
		return response()->json( $data, $data['code'] );
	}

	public function showByDocument( $document ){
		// Buscar el contratista por el id ingresado en la url
		$contractor = Contractor::where('documento', $document)->first();

		if( is_object($contractor) ){
			$data = array(
				'status'					=> 'success',
				'code'						=> 200,
				'contractor'				=> $contractor
			);
		} else {
			$data = array(
				'status'					=> 'error',
				'code'						=> 400,
				'message'					=> 'No existe ningún contratista con el número de documento '. $document
			);
		}

		// Devolver respuesta
		return response()->json( $data, $data['code'] );
	}

	// =================================================================================
	// =======================Funciones para guardar los datos==========================
	// =================================================================================
	public function store( Request $request ){
		// Extrar el json del request
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		// Verificar si se ingresaron bien los datos
		if( is_object($params) ){
			// Validar los datos ingresados
			$validate = \Validator::make($params_array, [
				'tipoDocumento'				=> 'required|numeric',
				'documento'					=> 'required|numeric|unique:contractors',
				'nombres'					=> 'required|regex:/^[\pL\s\-]+$/u',
				'apellidos'					=> 'required|regex:/^[\pL\s\-]+$/u',
				'profesion'					=> 'nullable',
				'sexo'						=> 'required|numeric'

			]);
			if( $validate->fails() ){
				$data = array(
					'status'				=> 'error',
					'code'					=> 400,
					'message'				=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
					'errors'				=> $validate->errors()
				);
			} else {
				// Guardar el registro en la base de datos
				$contractor = new Contractor();

				$contractor->tipoDocumento 	= $params->tipoDocumento;
				$contractor->documento 		= $params->documento;
				$contractor->nombres 		= strtoupper( $params->nombres );
				$contractor->apellidos 		= strtoupper( $params->apellidos );
				$contractor->profesion 		= strtoupper( $params->profesion );
				$contractor->sexo 			= $params->sexo;

				$contractor->save();

				$data = array(
					'status'				=> 'success',
					'code'					=> 201,
					'message'				=> 'Se ha realizado correctamente un nuevo registro',
					'contractor'			=> $contractor
				);
			}
		} else {
			$data = array(
				'status'					=> 'error',
				'code'						=> 500,
				'message'					=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
			);
		}

		// Devolver respuesta
		return response()->json( $data, $data['code'] );
	}

	// =================================================================================
	// ======================Funciones para actualizar los datos========================
	// =================================================================================
	public function update( $id, Request $request ){
		$contractor = Contractor::find($id);

		if( is_object($contractor) ){
			// Extrar el json del request
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);

			if( is_object($params) ){
				// Validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'tipoDocumento'				=> 'required|numeric',
					'documento'					=> 'required|numeric|unique:contractors,documento,'.$id,
					'nombres'					=> 'required|regex:/^[\pL\s\-]+$/u',
					'apellidos'					=> 'required|regex:/^[\pL\s\-]+$/u',
					'profesion'					=> 'nullable',
					'sexo'						=> 'required|numeric'

				]);
				if( $validate->fails() ){
					$data = array(
						'status'				=> 'error',
						'code'					=> 400,
						'message'				=> 'La validación de datos ha fallado. Comuniquese con el administrador de la plataforma',
						'errors'				=> $validate->errors()
					);
				} else {
					// Eliminar los datos que no se desean actualizar
					unset( $params_array['id'] );
					unset( $params_array['created_at'] );
					unset( $params_array['updated_at'] );

					// Convertir a mayusculas
					$params_array['nombres'] = strtoupper( $params_array['nombres'] );
					$params_array['apellidos'] = strtoupper( $params_array['apellidos'] );
					$params_array['profesion'] = strtoupper( $params_array['profesion'] );

					// Actualizar los datos
					$contractor = Contractor::where( 'id', $id )
											 ->update( $params_array );
					if( $contractor != 0 ){
						$data = array(
							'status'			=> 'success',
							'code'				=> 201,
							'message'			=> 'Se ha actualizado el registro '.$id.' correctamente',
							'changes'			=> $params_array
						);
					} else {
						$data = array(
							'status'			=> 'error',
							'code'				=> 400,
							'message'			=> 'No se ha podido actualizar el registro solicitado '.$id
						);
					}						 
				}
			} else {
				$data = array(
					'status'					=> 'error',
					'code'						=> 500,
					'message'					=> 'Se han ingresado los datos al servidor de manera incorrecta. Error en el servicio'
				);
			}
		} else {
			$data = array(
				'status'						=> 'error',
				'code'							=> 400,
				'message'						=> 'El registro que está intentando actualizar no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json( $data, $data['code'] );
	}

	// =================================================================================
	// =======================Funciones para eliminar los datos=========================
	// =================================================================================
	public function destroy( $id ){
		// Verificar si existe el registro que se desea eliminar
		$contractor = Contractor::find($id);

		if( is_object($contractor) ){
			$documento = $contractor->documento;

			$contractor->delete();

			$data = array(
				'status'						=> 'success',
				'code'							=> 200,
				'message'						=> 'El contratista '.$documento.' se ha eliminado correctamente'
			);
		} else {
			$data = array(
				'status'						=> 'error',
				'code'							=> 400,
				'message'						=> 'El registro que está intentando eliminar no existe en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json( $data, $data['code'] );
	}
}