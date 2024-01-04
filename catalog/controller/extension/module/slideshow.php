<?php
class ControllerExtensionModuleSlideshow extends Controller {
	public function index($setting) {
		static $module = 0;		

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
		
		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {

				$result_image = $result['image'];
				

				if ( preg_match("/.mp4$/", $result_image) ) {
					$video = 'image/'.preg_replace('/.*\/image/', '/image', $result_image);	
					$image = '';
				}else{
					$video = false;
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				}

				$bgcolor = '';

				if (!empty($result['bgcolor'])) {
					$bgcolor = $result['bgcolor'];
				}

				$data['banners'][] = array(
					'title' => $result['title'],
					'button_text' => $result['button_text'],
					'title_one' => $result['title_one'],
					'link'  => $result['link'],
					'image' => $image,
					'video' => $video,
					'bgcolor' => $bgcolor
				);
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/slideshow', $data);
	}
}