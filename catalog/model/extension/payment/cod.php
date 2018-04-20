<?php
class ModelExtensionPaymentCOD extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/cod');

		$status = true;
		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'cod',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_cod_sort_order')
			);
		}

		return $method_data;
	}
}
