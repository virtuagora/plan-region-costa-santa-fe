<?php use Illuminate\Database\Eloquent\Model as Eloquent;

class Departamento extends Eloquent {
    protected $table = 'departamentos';
    protected $visible = ['id', 'nombre', 'localidades'];
    protected $with = ['localidades'];

    public function nodo() {
        return $this->belongsTo('Nodos');
    }

    public function localidades() {
        return $this->hasMany('Localidad');
    }
}