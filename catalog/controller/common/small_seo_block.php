<?php
class ControllerCommonSmallSeoBlock extends Controller {
    public function index() {
        $this->load->model('common/reviews_form');

		$description = $this->model_setting_module->getModule(34)['module_description'][1]['description'];
        return htmlspecialchars_decode($description);
    }

    public function addReview(){
        $this->load->model('common/reviews_form');

        $name = $_POST['name'];
        $comment = $_POST['comment'];

        $this->model_common_reviews_form->addReview($name, $comment);
    }
}