<?php use Augusthur\Validation as Validate;

class TestimonioCtrl extends Controller {

    public function verCrear() {
        $this->render('costa/contenido/opinion/crear.twig');
    }

    public function eliminar($idTes) {
        $vdt = new Validate\QuickValidator([$this, 'notFound']);
        $vdt->test($idTes, new Validate\Rule\NumNatural());
        $testimonio = Testimonio::findOrFail($idTes);
        $testimonio->delete();
        $this->flash('success', 'El testimonio ha sido eliminado exitosamente.');
        $this->redirectTo('shwIndexAdmin');
    }

    public function crear() {
        $req = $this->request;
        $vdt = $this->validarTestimonio($req->post());
        $testimonio = new Testimonio;
        $testimonio->testimonio = $vdt->getData('testimonio');
        $testimonio->orden = $vdt->getData('orden');
        $testimonio->persona = $vdt->getData('persona');
        $testimonio->cargo = $vdt->getData('cargo');
        $testimonio->save();
        $this->flash('success', 'La opinión se creó exitosamente.');
        $this->redirectTo('shwIndexAdmin');
    }

    // TODO habria que hacer un ELIMINAR opinion.
    private function validarTestimonio($data) {
        $vdt = new Validate\Validator();
        $vdt->addRule('testimonio', new Validate\Rule\MinLength(2))
            ->addRule('persona', new Validate\Rule\MinLength(2))
            ->addRule('cargo', new Validate\Rule\MinLength(2))
            ->addRule('orden', new Validate\Rule\NumNatural());
        if (!$vdt->validate($data)) {
            throw new TurnbackException($vdt->getErrors());
        }
        return $vdt;
    }
}
