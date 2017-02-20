<?php use Augusthur\Validation as Validate;

class EventoCtrl extends Controller {

    // TODO No  lo usamos
    public function ver($idEve) {
        $vdt = new Validate\QuickValidator([$this, 'notFound']);
        $vdt->test($idEve, new Validate\Rule\NumNatural());
        $evento = Evento::with('comentarios')->findOrFail($idEve);
        $participe = $evento->usuarios()->where('usuario_id', $this->session->user('id'))->first();
        $participantes = $evento->usuarios()->toArray();
        $comentarios = $evento->comentarios->toArray();
        $datosEven = $evento->toArray();
        $datosEven['interesados'] = $evento->usuarios()->count();
        $datosPart = $participe? $participe->toArray(): null;
        $this->render('lpe/contenido/evento/ver.twig', [
            'evento' => $datosEven,
            'comentarios' =>  $comentarios,
            'participacion' => $datosPart,
            'participantes' => $participantes
        ]);
    }
    
    // 
    public function listar() {
        $eventos = Evento::all()->sortBy('fecha');
        $this->render('lpe/contenido/evento/listar.twig', [
            'eventos' => $eventos
        ]);
    }

    public function participar($idEve) {
        $vdt = new Validate\QuickValidator([$this, 'notFound']);
        $vdt->test($idEve, new Validate\Rule\NumNatural());
        $req = $this->request;
        $usuario = $this->session->getUser();
        $evento = Evento::findOrFail($idEve);
        $hoy = Carbon\Carbon::now();
        if ($hoy->gt($evento->fecha)) {
            throw new TurnbackException('El evento ya ha ocurrido.');
        }
        $participe = $evento->usuarios()->where('usuario_id', $usuario->id)->first();
        if (is_null($participe)) {
            $evento->usuarios()->attach($usuario->id);
        } else {
            $evento->usuarios()->detach($usuario->id);
        }
        $this->flash('success', 'Su participación fue registrada exitosamente.');
        $this->redirectTo('shwEvento', array('idEve' => $evento->id));
    }

    public function verCrear() {
        $this->render('costa/contenido/evento/crear.twig');
    }
    // TODO aca te paso estos datos para evento.
    // titulo - text - Titulo evento
    // fecha_hasta - date -(si o si)
    // fecha_hasta - date - (puede ser null)
    // info - text - Informacion evento, tipo a las 16Hs en San Martin 1555
    // cuerpo - text -
    // url - text - para mas info
    public function crear() {
        $req = $this->request;
        $vdt = $this->validarEvento($req->post());
        $autor = $this->session->getUser();
        $evento = new Evento;
        $evento->descripcion = $vdt->getData('descripcion');
        $evento->lugar = $vdt->getData('lugar');
        $evento->fechaDesde = Carbon\Carbon::parse($vdt->getData('fecha_desde'));
        $evento->fechaHasta = Carbon\Carbon::parse($vdt->getData('fecha_hasta'));
        $evento->titulo = $vdt->getData('titulo');
        $evento->info = $vdt->getData('info');
        $evento->autor()->associate($autor);
        $evento->save();
        $this->flash('success', 'Su evento fue creado exitosamente.');
        $this->redirectTo('shwListaEvento');
    }

    public function verModificar($idEve) {
        $vdt = new Validate\QuickValidator([$this, 'notFound']);
        $vdt->test($idEve, new Validate\Rule\NumNatural());
        $evento = Evento::findOrFail($idEve);
        $datos = $evento->toArray();
        $this->render('lpe/contenido/evento/modificar.twig', ['evento' => $datos]);
    }

    public function modificar($idEve) {
        $vdt = new Validate\QuickValidator([$this, 'notFound']);
        $vdt->test($idEve, new Validate\Rule\NumNatural());
        $evento = Evento::findOrFail($idEve);
        $usuario = $this->session->getUser();
        $req = $this->request;
        $vdt = $this->validarEvento($req->post());
        $evento->cuerpo = $vdt->getData('cuerpo');
        $evento->lugar = $vdt->getData('lugar');
        $evento->fecha = Carbon\Carbon::parse($vdt->getData('fecha'));
        $evento->save();
        $evento->titulo = $vdt->getData('titulo');
        $evento->save();
        $this->flash('success', 'Los datos del evento fueron modificados exitosamente.');
        $this->redirectTo('shwEvento', ['idEve' => $idEve]);
    }

    public function eliminar($idEve) {
        $vdt = new Validate\QuickValidator([$this, 'notFound']);
        $vdt->test($idEve, new Validate\Rule\NumNatural());
        $evento = Evento::findOrFail($idEve);
        $evento->delete();
        $this->flash('success', 'El evento ha sido eliminado exitosamente.');
        $this->redirectTo('shwIndex');
    }

    private function validarEvento($data) {
        $vdt = new Validate\Validator();
        $vdt->addRule('titulo', new Validate\Rule\MinLength(1))
            ->addRule('lugar', new Validate\Rule\MinLength(1))
            ->addRule('fecha_desde', new Validate\Rule\Date('Y-m-d'))
            ->addRule('fecha_hasta', new Validate\Rule\Date('Y-m-d'))
            ->addRule('descripcion', new Validate\Rule\MinLength(1))
            ->addRule('info', new Validate\Rule\MinLength(1))
            ->addOptional('fecha_hasta')
            ->addOptional('info');
        if (!$vdt->validate($data)) {
            throw new TurnbackException($vdt->getErrors());
        }
        return $vdt;
    }

}
