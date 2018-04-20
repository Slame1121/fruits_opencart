<?php
class ModelExtensionShippingCitylink extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/citylink');


		$status = true;
		$method_data = array();

		if ($status) {
			$cost = 0;
			$weight = $this->cart->getWeight();

			$rates = explode(',', $this->config->get('shipping_citylink_rate'));

			foreach ($rates as $rate) {
				$data = explode(':', $rate);

				if ($data[0] >= $weight) {
					if (isset($data[1])) {
						$cost = $data[1];
					}

					break;
				}
			}

			$quote_data = array();

			//if ((float)$cost) {
				$quote_data['citylink'] = array(
					'code'         => 'citylink.citylink',
					'title'        => $this->language->get('text_title') . '  ',
					'cost'         => $cost,
					'tax_class_id' => $this->config->get('shipping_citylink_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('shipping_citylink_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
				);

				$method_data = array(
					'code'       => 'citylink',
					'title'      => $this->language->get('text_title'),
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('shipping_citylink_sort_order'),
					'error'      => false
				);
			//}
		}

		return $method_data;
	}
}