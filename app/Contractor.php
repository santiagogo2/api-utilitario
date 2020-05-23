<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
	protected $table = 'contractors';

	protected $fillable = [
		'tipoDocumento', 'documento', 'nombres', 'apellidos', 'profesion', 'sexo'
	];

	public function Contract(){
		return $this->hasMany('App\Contract');
	}
}
