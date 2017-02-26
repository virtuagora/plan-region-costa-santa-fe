<?php use Illuminate\Database\Eloquent\Model as Eloquent;

class Localidad extends Eloquent {
    protected $table = 'localidades';
    protected $visible = ['id', 'nombre'];

    public function departamento() {
        return $this->belongsTo('Departamento');
    }
}