<?php
class ControllerInformationAboutUs extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('information/contact');

		$data = [];

		$this->response->setOutput($this->load->view('information/contact', $data));
	}

}
