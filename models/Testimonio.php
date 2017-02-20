<?php use Illuminate\Database\Eloquent\Model as Eloquent;

class Testimonio extends Eloquent {

    protected $table = 'testimonios';
    protected $visible = ['id', 'orden', 'persona', 'cargo', 'testimonio'];

}
