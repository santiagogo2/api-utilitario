<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
	protected $table = 'collaborator';

	protected $fillable = [
		'nombre','apellidos','tipoDocumento','documento','sexo','fechaNacimiento','edad','telefono','arl','aseguradora','direccion','fechaIngreso','fechaEgreso','fechaSintomas','nexo','fiebre','malestarGeneral','disnea','odinia','gastrointestinales','estornudo','mialgias','riesgoEndogeno','riesgoExogeno','factoresExposicion','manejo','remitido','fechaTomaMuestra','fechaResultado','diasTranscurridos','estado','trabajo','fechaSegundaPrueba',
	];
}