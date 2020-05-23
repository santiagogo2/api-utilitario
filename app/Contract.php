<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
	protected $table = "contracts";

	protected $fillable = [
		'numero', 'objetoContractual', 'ordenadorGasto', 'nombreSupervisor', 'cargoSupervisor', 'modalidad',
		'valorHora', 'promedioHoras', 'honorarios', 'fechaInicio', 'fechaTerminacion', 'valorInicial',
		'CDP', 'fechaCDP', 'contractors_id',
	];

	public function Contractor(){
		return $this->belongsTo('App\Contractor', 'contractors_id');
	}
}