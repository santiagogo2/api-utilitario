<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuthMiddleware
{
	/**
	* Handle an incoming request.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, Closure $next)
	{
		$token = $request->header('Authorization');

		if( $token ){
			$jwtAuth = new \JwtAuth;
			$checkToken = $jwtAuth->checkToken($token, true);
			
			if($checkToken && $checkToken->role == 'ADMIN_ROLE'){
				return $next($request);
			} else {
				$data = array(
					'status'		=> 'error',
					'code'			=> 401,
					'message'		=> 'Usted no tiene permisos para realizar esta acción'
				);
			}			
		} else {
			$data = array(
				'status'		=> 'error',
				'code'			=> 400,
				'message'		=> 'No se ha ingresado la cabecera de autenticación'
			);
		}
		return response()->json($data, $data['code']);
	}
}