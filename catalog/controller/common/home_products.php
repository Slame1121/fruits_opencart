<?php
class ControllerCommonHomeProducts extends Controller {
	public function index() {

		$this->load->model('catalog/product');
		$this->load->model('setting/module');
		$this->load->model('tool/image');
		//Акции
		$special_poducts = $this->model_catalog_product->getProducts(['filter_specials' => true,'sort' => 'p.price','start' => 1, 'limit' => 10]);
		if($special_poducts){
			foreach ($special_poducts as $key => $product) {
				$special_poducts[$key]['image'] =  $this->model_tool_image->resize($product['image'], 260, 160);
				$special_poducts[$key]['price'] = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$special_poducts[$key]['special_price'] = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$special_poducts[$key]['link'] = $this->url->link('product/product','product_id=' . $product['product_id']);
				$special_poducts[$key]['liked'] = in_array((int)$product['product_id'], $this->session->data['wishlist']);
			}
		}
		//Рекомендуемые модуль в админке id 28
		$recomended_ids = $this->model_setting_module->getModule(28)['product'];
		$recomended_products = [];

		if($recomended_ids){
			foreach ($recomended_ids as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				$product_info['image'] =  $this->model_tool_image->resize($product_info['image'], 260, 160);
				$product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$product_info['link'] = $this->url->link('product/product','product_id=' . $product_info['product_id']);
				$product_info['liked'] = in_array((int)$product_info['product_id'], $this->session->data['wishlist']);
				$recomended_products[] = $product_info;
			}
		}

		//Новые поступления
		$new_products =  $this->model_catalog_product->getProducts(['sort' => 'p.product_id','order' => 'DESC','start' => 1, 'limit' => 10]);
		if($new_products){
			foreach ($new_products as $key => $product) {
				$new_products[$key]['image'] =  $this->model_tool_image->resize($product['image'], 260, 160);
				$new_products[$key]['price'] = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$new_products[$key]['special_price'] = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$new_products[$key]['link'] = $this->url->link('product/product','product_id=' . $product['product_id']);
				$new_products[$key]['liked'] = in_array((int)$product['product_id'], $this->session->data['wishlist']);
			}
		}
		$data = [
			'recomended' => $recomended_products,
			'new' => $new_products,
			'special' => $special_poducts
		];
		return $this->load->view('common/home_products', $data);
	}

}