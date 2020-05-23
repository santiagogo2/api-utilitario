<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;

class ContractController extends Controller
{
	// =================================================================================
	// =======================Funciones para extraer los datos==========================
	// =================================================================================
	public function index(){
		// Buscar todos los contratos en la base de datos
		$contracts = Contract::with('Contractor')
							 ->get();

		if( is_object($contracts) && sizeof($contracts) != 0 ){
			$data = array(
				'status'					=> 'success',
				'code'						=> 200,
				'contracts'					=> $contracts
			);
		} else {
			$data = array(
				'status'					=> 'error',
				'code'						=> 400,
				'message'					=> 'No se han encontrado contratos en la base de datos'
			);
		}

		// Devolver respuesta
		return response()->json( $data, $data['code'] );
	}

	public function show( $id ){
		// Buscar el contrato por el id ingresado en la url
		$contract = Contract::with('Contractor')
							->find($id);

		if( is_object($contract) ){
			$data = array(
				'status'					=> 'success',
				'code'						=> 200,
				'contract'					=> $contract
			);
		} else {
			$data = array(
				'status'					=> 'error',
				'code'						=> 400,
				'message'					=> 'No se han encontrado ningún contrato con el id: '. $id
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
				'numero'					=> 'required|numeric', // Debería ser único
				'objetoContractual'			=> 'required',
				'ordenadorGasto'			=> 'required|numeric',
				'nombreSupervisor'			=> 'required|regex:/^[\pL\s\-]+$/u',
				'cargoSupervisor'			=> 'required|regex:/^[\pL\s\-]+$/u',
				'modalidad'					=> 'required|numeric',
				'valorHora'					=> 'required|numeric',
				'promedioHoras'				=> 'required|numeric',
				'honorarios'				=> 'required|numeric',
				'fechaInicio'				=> 'required',
				'fechaTerminacion'			=> 'required',
				'valorInicial'				=> 'required|numeric',
				'CDP'						=> 'required|numeric',
				'fechaCDP'					=> 'required',
				'contractors_id'			=> 'required|numeric',
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
				$contract = new Contract();

				$contract->numero 			= $params->numero;
				$contract->objetoContractual= $params->objetoContractual;
				$contract->ordenadorGasto 	= $params->ordenadorGasto;
				$contract->nombreSupervisor = strtoupper( $params->nombreSupervisor );
				$contract->cargoSupervisor 	= strtoupper( $params->cargoSupervisor );
				$contract->modalidad 		= $params->modalidad;
				$contract->valorHora 		= $params->valorHora;
				$contract->promedioHoras 	= $params->promedioHoras;
				$contract->honorarios 		= $params->honorarios;
				$contract->fechaInicio 		= $params->fechaInicio;
				$contract->fechaTerminacion = $params->fechaTerminacion;
				$contract->valorInicial 	= $params->valorInicial;
				$contract->CDP 				= $params->CDP;
				$contract->fechaCDP 		= $params->fechaCDP;
				$contract->contractors_id 	= $params->contractors_id;

				$contract->save();

				$data = array(
					'status'				=> 'success',
					'code'					=> 201,
					'message'				=> 'Se ha realizado correctamente un nuevo registro',
					'contract'				=> $contract
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
		$contract = Contract::find($id);

		if( is_object($contract) ){
			// Extrar el json del request
			$json = $request->input('json', null);
			$params = json_decode($json);
			$params_array = json_decode($json, true);

			if( is_object($params) ){
				// Validar los datos ingresados
				$validate = \Validator::make($params_array, [
					'numero'					=> 'required|numeric', // Debería ser único
					'objetoContractual'			=> 'required',
					'ordenadorGasto'			=> 'required|numeric',
					'nombreSupervisor'			=> 'required|regex:/^[\pL\s\-]+$/u',
					'cargoSupervisor'			=> 'required|regex:/^[\pL\s\-]+$/u',
					'modalidad'					=> 'required|numeric',
					'valorHora'					=> 'required|numeric',
					'promedioHoras'				=> 'required|numeric',
					'honorarios'				=> 'required|numeric',
					'fechaInicio'				=> 'required',
					'fechaTerminacion'			=> 'required',
					'valorInicial'				=> 'required|numeric',
					'CDP'						=> 'required|numeric',
					'fechaCDP'					=> 'required',
					'contractors_id'			=> 'required|numeric',

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
					$params_array['nombreSupervisor'] = strtoupper( $params_array['nombreSupervisor'] );
					$params_array['cargoSupervisor'] = strtoupper( $params_array['cargoSupervisor'] );

					// Actualizar los datos
					$contract = Contract::where( 'id', $id )
										->update( $params_array );
					if( $contract != 0 ){
						$data = array(
							'status'			=> 'success',
							'code'				=> 201,
							'message'			=> 'Se ha actualizado el contrato '.$params_array['numero'].' correctamente',
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
		$contract = Contract::find($id);

		if( is_object($contract) ){
			$numero = $contract->numero;

			$contract->delete();

			$data = array(
				'status'						=> 'success',
				'code'							=> 200,
				'message'						=> 'El contrato '.$numero.' se ha eliminado correctamente'
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