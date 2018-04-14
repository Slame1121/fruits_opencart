<?php
class ControllerCommonContactForm extends Controller {
    public function index() {

		$data = [];

        return $this->load->view('common/contact_form', $data);
    }

}