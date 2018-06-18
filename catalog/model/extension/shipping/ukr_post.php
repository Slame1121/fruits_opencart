<?php
class ModelExtensionShippingUkrPost extends Model {
    function getQuote($address) {
        $this->load->language('extension/shipping/ukr_post');

        $status = true;

        $method_data = array();

        if ($status) {
            $quote_data = array();

            $quote_data['ukr_post'] = array(
                'code'         => 'ukr_post.ukr_post',
                'title'        => $this->language->get('text_description'),
                'cost'         => 0.00,
                'tax_class_id' => 0,
                'text'         => $this->currency->format(0.00, $this->session->data['currency'])
            );

            $method_data = array(
                'code'       => 'ukr_post',
                'title'      => $this->language->get('text_title'),
                'quote'      => $quote_data,
                'sort_order' => $this->config->get('shipping_ukr_post_sort_order'),
                'error'      => false
            );
        }

        return $method_data;
    }
}