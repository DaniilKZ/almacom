<?php
class ControllerCommonPricelist extends Controller {
	public function index() { 

		$this->document->setTitle('Загрузка прайс листа');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['breadcrumbs'] = array();
 
		$data['breadcrumbs'][] = array(
			'text' => 'Загрузка прайс листа',
			'href' => $this->url->link('common/pricelist', 'user_token=' . $this->session->data['user_token'], true)
		);
	   		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['response_redirect_price_upload'] = "";
 

		if ( isset($_GET['response']) && $_GET['response'] == 'succes' ) {
			$data['response_redirect_price_upload'] = "Файл корректен и был успешно загружен.\n";
		}elseif(isset($_GET['response']) &&  $_GET['response'] == 'fail'){
			$data['response_redirect_price_upload'] = "Ошибка загрузки!";
		}


		$this->response->setOutput($this->load->view('common/pricelist', $data));
	}

	public function upload(){
			$data['upload_price_message'] = '';

			$data['user_token'] = $this->session->data['user_token']; 

			$uploaddir = '/var/www/vhosts/almacom.kz/httpdocs/pricelist/';
   
				if (!empty($this->request->files['uploadfile'])) {  

					$files = glob($uploaddir.'/*');  
					foreach($files as $file){ 
					  if(is_file($file)) {
					    unlink($file); // delete file
					  }
					} 

					$temp = explode(".", $this->request->files['uploadfile']['name']); 
					$newfilename = $uploaddir."pricelist_".date('d_m_Y').".".end($temp);
  
					if ( move_uploaded_file($this->request->files['uploadfile']['tmp_name'], $newfilename ) ) {
						$this->response->redirect($this->url->link('common/pricelist', 'user_token='.$this->session->data['user_token'].'&response=succes', '')); 
					} else {
						$this->response->redirect($this->url->link('common/pricelist', 'user_token='.$this->session->data['user_token'].'&response=fail', ''));
					}
				}
 
	}
} 
  