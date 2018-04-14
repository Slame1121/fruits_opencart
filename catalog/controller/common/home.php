<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}
		$data['home_products'] = $this->load->controller('common/home_products');
		$data['category_catalog'] = $this->load->controller('common/category_catalog');
		$data['contact_form'] = $this->load->controller('common/contact_form');
		$data['big_seo_tabs_block'] = $this->load->controller('common/big_seo_tabs_block');
		$data['small_seo_block'] = $this->load->controller('common/small_seo_block');
        $data['reviews_form'] = $this->load->controller('common/reviews_form');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
