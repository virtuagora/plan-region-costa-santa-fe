<?php use Illuminate\Database\Eloquent\Model as Eloquent;

class Nodo extends Eloquent {
    protected $table = 'nodos';
    protected $visible = ['id', 'nombre', 'region'];
}