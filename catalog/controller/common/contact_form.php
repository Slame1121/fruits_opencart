<?php
class ControllerCommonContactForm extends Controller {
    public function index() {

		$data = [];
		$data['telephone'] = $this->config->get('config_telephone');
		$data['telephone_2'] = $this->config->get('config_telephone_2');
		$data['config_email'] = $this->config->get('config_email');
		$data['config_address'] = $this->config->get('config_address');
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