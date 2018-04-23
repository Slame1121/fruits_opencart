<?php
class ControllerCommonContactForm extends Controller {
    public function index() {

		$data = [];

        return $this->load->view('common/contact_form', $data);
    }

    public function addFeedback(){
        $this->load->model('common/contact_form');

        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $this->model_common_contact_form->addFeedback($name, $email, $message);
    }
}