<?php

class Derecho extends Contenible {
    protected $table = 'derechos';
    protected $dates = ['deleted_at'];
    protected $visible = ['id', 'descripcion', 'secciones', 'video', 'imagen'];
    protected $with = ['secciones'];

    public function secciones() {
        return $this->hasMany('Seccion');
    }
    
    public function votos() {
        return $this->hasManyThrough('VotoSeccion', 'Seccion', 'derecho_id', 'seccion_id');
    }
}
