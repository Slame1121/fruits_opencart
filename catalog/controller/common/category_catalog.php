<?php
class ControllerCommonCategoryCatalog extends Controller {

	public function getChilds(){

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$category_id = (int)$_POST['category_id'];
		$children_data = array();
		$category_info = $this->model_catalog_category->getCategory($category_id);
		$children = $this->model_catalog_category->getCategories($category_id);

		foreach($children as $child) {
			$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

			$children_data[] = array(
				'category_id' => $child['category_id'],
				'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
				'href' => $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'])
			);
		}
		$rsponse = [
			'childs' => $children_data,
			'name' => $category_info['name'],
			'link' => html_entity_decode( $this->url->link('product/category', 'path=' . $category_id))
		];
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($rsponse));
	}

	public function index() {

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
		$this->load->model('tool/image');


		$categories = $this->model_catalog_category->getCategories(0);
		$data = [];
		$data['catalog'] = $this->url->link('product/category', 'path=0');
		foreach ($categories as $category) {



			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);

			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'description' => htmlspecialchars_decode(mb_substr ($category['description'], 0, 140)).'' .(utf8_strlen($category['description']) > 140 ?  '...' : ''),
				'image'       => $this->model_tool_image->resize($category['image'], 150, 215),
				'active_image'       => $this->model_tool_image->resize($category['active_image'], 400, 340),
				'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
				'href'        => $this->url->link('product/category', 'path=' . $category['category_id']),
				'top_gradient'=> $category['top_gradient'],
				'bottom_gradient'=> $category['bottom_gradient'],
				'button_color'=> $category['button_color'],
			);
		}
		return $this->load->view('common/category_catalog', $data);
	}

}