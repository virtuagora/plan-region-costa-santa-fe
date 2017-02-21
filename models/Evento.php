<?php use Illuminate\Database\Eloquent\Model as Eloquent;

class Evento  extends Eloquent {
    protected $table = 'eventos';
    protected $dates = ['fecha_desde', 'fecha_hasta'];
    protected $visible = ['id', 'titulo', 'descripcion', 'fecha_desde',
        'fecha_hasta', 'lugar', 'info'];
    
    public function autor() {
        return $this->belongsTo('Usuario');
    }

    public function getIdentidadAttribute() {
        return $this->titulo;
    }
}
