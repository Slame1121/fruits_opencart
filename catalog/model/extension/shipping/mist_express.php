<?php
class ModelExtensionShippingMistExpress extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/mist_express');

		$status = true;

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['mist_express'] = array(
				'code'         => 'mist_express.mist_express',
				'title'        => $this->language->get('text_description'),
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00, $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'mist_express',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_mist_express_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}