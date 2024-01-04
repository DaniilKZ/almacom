<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {
			
			if ($setting['this_module_id'] == 28) {

				$results = $this->model_catalog_product->getProducts(array('filter_category_id' => 59));
				$products = array_slice($results, 0, (int)$setting['limit']);

				foreach ($products as $product_info) {

					if ($product_info) {
						if ($product_info['image']) {
							$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
						}

						if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
							$price = false;
						}

						if ((float)$product_info['special']) {
							$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
							$special = false;
						}

						if ($this->config->get('config_tax')) {
							$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
						} else {
							$tax = false;
						}

						if ($this->config->get('config_review_status')) {
							$rating = $product_info['rating'];
						} else {
							$rating = false;
						}

						$discount_percent = 0;
						if ( !empty($price) && !empty($special) ) {
							$discount_percent = round((floatval($price) - floatval($special)) / floatval($price), 2) * 100;
						}


						// CUSTOM CODE -- ADD PRODUCT OPTIONS (TO IDENTIFY IF PRICE IS EXACT OR NOT)

						$data_options = array();

						$result_product_id = $product_info['product_id'];

						foreach ($this->model_catalog_product->getProductOptions($result_product_id) as $option) {

							$data_options[] = array(
								'product_option_id'    => $option['product_option_id'],
								'option_id'            => $option['option_id'],
								'name'                 => $option['name'],
								'type'                 => $option['type']
							);
						}

						$this->load->language('product/product');
						if ($product_info['quantity'] <= 0) { 
							$stock = '<div class="card-product-presence"><i class="sprite sprite-clock"><svg><use xlink:href="#clock"></use></svg></i><span>' . $product_info['stock_status'] . '</span></div>';
						} elseif ($this->config->get('config_stock_display')) {
							$stock = $product_info['quantity'];
						} else { 
							$stock = '<div class="card-product-presence"><i class="sprite sprite-check"><svg><use xlink:href="#check"></use></svg></i><span>' . $this->language->get('text_instock') . '</span></div>';
						}

						$data['products'][] = array(

							'stock'  => $stock,

							'jan' => $product_info['jan'],
							'product_id'  => $product_info['product_id'],
							'thumb'       => $image,
							'name'        => $product_info['name'],
							'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
							'price'       => $price,
							'special'     => $special,
							'tax'         => $tax,
							'rating'      => $rating,
							'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
							'discount_percent' => $discount_percent,
							'options' => $data_options,
							'model'  => $product_info['model'],
							'ean' => $product_info['ean']
						);
					}
				}

			} else {

				$products = array_slice($setting['product'], 0, (int)$setting['limit']);

				foreach ($products as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);

					if ($product_info) {
						if ($product_info['image']) {
							$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
						}

						if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
							$price = false;
						}

						if ((float)$product_info['special']) {
							$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
							$special = false;
						}

						if ($this->config->get('config_tax')) {
							$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
						} else {
							$tax = false;
						}

						if ($this->config->get('config_review_status')) {
							$rating = $product_info['rating'];
						} else {
							$rating = false;
						}

						$discount_percent = 0;
						if ( !empty($price) && !empty($special) ) {
							$discount_percent = round((floatval($price) - floatval($special)) / floatval($price), 2) * 100;
						}

						// CUSTOM CODE -- ADD PRODUCT OPTIONS (TO IDENTIFY IF PRICE IS EXACT OR NOT)

						$data_options = array();

						$result_product_id = $product_info['product_id'];

						foreach ($this->model_catalog_product->getProductOptions($result_product_id) as $option) {

							$data_options[] = array(
								'product_option_id'    => $option['product_option_id'],
								'option_id'            => $option['option_id'],
								'name'                 => $option['name'],
								'type'                 => $option['type']
							);
						}

						$this->load->language('product/product');
						if ($product_info['quantity'] <= 0) { 
							$stock = '<div class="card-product-presence"><i class="sprite sprite-clock"><svg><use xlink:href="#clock"></use></svg></i><span>' . $product_info['stock_status'] . '</span></div>';
						} elseif ($this->config->get('config_stock_display')) {
							$stock = $product_info['quantity'];
						} else { 
							$stock = '<div class="card-product-presence"><i class="sprite sprite-check"><svg><use xlink:href="#check"></use></svg></i><span>' . $this->language->get('text_instock') . '</span></div>';
						}

						$data['products'][] = array(

							'stock'  => $stock,

							'jan' => $product_info['jan'],
							'product_id'  => $product_info['product_id'],
							'thumb'       => $image,
							'name'        => $product_info['name'],
							'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
							'price'       => $price,
							'special'     => $special,
							'tax'         => $tax,
							'rating'      => $rating,
							'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
							'discount_percent' => $discount_percent,
							'options' => $data_options,
							'model'  => $product_info['model'],
							'ean' => $product_info['ean']
						);
					}
				}
			}
		}

		$data['module_name'] = $setting['name'];
		$data['id'] = $setting['this_module_id'];

		$product_total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => 18));
		$data['product_total'] = $product_total;

		if ($data['products']) {
			return $this->load->view('extension/module/featured', $data);
		}
	}
}