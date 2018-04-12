<?php
class ControllerCommonCategoryCatalog extends Controller {

	public function getChilds(){

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$category_id = (int)$_POST['category_id'];
		$children_data = array();

		$children = $this->model_catalog_category->getCategories($category_id);

		foreach($children as $child) {
			$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

			$children_data[] = array(
				'category_id' => $child['category_id'],
				'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
				'href' => $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'])
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($children_data));
	}

	public function index() {

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$categories = $this->model_catalog_category->getCategories(0);
		$data = [];
		foreach ($categories as $category) {



			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);

			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'description' => substr(htmlspecialchars_decode($category['description']), 0, 100),
				'image'       => $this->model_tool_image->resize($category['image'], 170, 340),
				'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),

				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);
		}
		return $this->load->view('common/category_catalog', $data);
	}

}