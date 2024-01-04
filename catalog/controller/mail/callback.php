<?php 

class ControllerMailCallback extends Controller {
    public function index() {
        date_default_timezone_set('Etc/UTC');
		require 'PHPMailer-5.2-stable/PHPMailerAutoload.php';

		if ( !empty($_POST['name']) && !empty($_POST['tel']) ) {

			$name = $_POST['name']; $tel = $_POST['tel'];

			include_once  '/var/www/vhosts/almacom.kz/httpdocs/AmoCRM/auth.php';

			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->isSMTP();
			$mail->Host = trim($this->config->get('config_mail_smtp_hostname'));
			$mail->SMTPAuth = true;
			//$mail->SMTPDebug = 3;
			$mail->Username = trim($this->config->get('config_mail_parameter'));
			$mail->Password = trim($this->config->get('config_mail_smtp_password'));
			$mail->SMTPSecure = 'ssl';
			$mail->Port = trim($this->config->get('config_mail_smtp_port'));
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			$mail->setFrom(trim($this->config->get('config_email')), 'Almacom');

			$mail->addReplyTo(trim($this->config->get('config_email')), 'Almacom.kz');

			$mails = explode(",", $this->config->get('config_mail_alert_email'));
			array_unshift($mails, $this->config->get('config_email'));
	    	for ($x = 0; $x < count($mails); $x++) {
	    		$mail->addAddress(trim($mails[$x]));
	    	}

			$mail->isHTML(true);
			$mail->Subject = 'Новая заявка на обратный звонок с сайта ALMACOM';
			$mail->Body =
				'Имя: <strong>' . $name . '</strong><br>' .
				'Номер телефона: <strong>' . $tel . '</strong><br>' .
			'';

			if(!$mail->send()) {
				echo 'Message could not be sent.';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				echo '<div class="order_call-in order_call-success"><img src="catalog/view/theme/almacom/img/check-mark.png" alt=""><p>Заявка успешно отправлена!</p><button class="oc-success-btn">Закрыть</button></div>';
			}
		} else {
			echo "Есть не заполненные поля в форме!";
		}

    
        // $this->response->redirect($this->url->link('information/contact/success')); //Если нужно редирект можно раскоментить
    }

    public function main() {
 
        session_start();

		date_default_timezone_set('Etc/UTC');

		require 'PHPMailer-5.2-stable/PHPMailerAutoload.php';
		require 'recaptcha/autoload.php';

		$secret = '6Lf5wsAaAAAAAG_yQHsd6xY-kHw4gzvEOxQ_MXNb';

		if (isset($_POST['g-recaptcha-response'])) {

			$recaptcha = new \ReCaptcha\ReCaptcha($secret);
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

			if ($resp->isSuccess()) {

		if ( !empty($_POST['your_name']) && !empty($_POST['your_phone']) ) {

				$name = $_POST['your_name']; $tel = $_POST['your_phone'];
				$message = (!empty($_POST['your_message'])) ? $_POST['your_message'] : "" ;
				include_once  '/var/www/vhosts/almacom.kz/httpdocs/AmoCRM/auth.php';
	 

					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail->isSMTP();
					$mail->Host = trim($this->config->get('config_mail_smtp_hostname'));
					$mail->SMTPAuth = true;
					//$mail->SMTPDebug = 3;
					$mail->Username = trim($this->config->get('config_mail_parameter'));
					$mail->Password = trim($this->config->get('config_mail_smtp_password'));
					$mail->SMTPSecure = 'ssl';
					$mail->Port = trim($this->config->get('config_mail_smtp_port'));
					$mail->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
						)
					);
					$mail->setFrom(trim($this->config->get('config_email')), 'Almacom');

					$mail->addReplyTo(trim($this->config->get('config_mail_parameter')), 'Almacom.kz');
					
					$mails = explode(",", $this->config->get('config_mail_alert_email'));
					array_unshift($mails, $this->config->get('config_email'));
			    	for ($x = 0; $x < count($mails); $x++) {
			    		$mail->addAddress(trim($mails[$x]));
			    	}

					$mail->isHTML(true);
					$mail->Subject = 'Новое письмо с сайта ALMACOM';
					$mail->Body =
						'Имя: <strong>' . $name . '</strong><br>' .
						'Номер телефона: <strong>' . $tel . '</strong><br>' .
						'Сообщение: <strong>' . $message . '</strong><br>' .
					'';

					if(!$mail->send()) {
						echo 'Message could not be sent.';
						echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
						echo '<div class="mi-form-success"><img src="catalog/view/theme/almacom/img/check-mark-blue.png" alt=""><p>Заявка успешно отправлена!</p></div>';
					}
				} else {
					echo "Есть не заполненные поля в форме!";
				}
			} else {
				echo "Код капчи не прошёл проверку на сервере";
			}
		} else {
			echo "Капча не существует";
		}
    }

    public function question() {

    	session_start();

		date_default_timezone_set('Etc/UTC');

		require 'PHPMailer-5.2-stable/PHPMailerAutoload.php';
		require 'recaptcha/autoload.php';

		$secret = '6Lf5wsAaAAAAAG_yQHsd6xY-kHw4gzvEOxQ_MXNb';

		if (isset($_POST['g-recaptcha-response'])) {

			$recaptcha = new \ReCaptcha\ReCaptcha($secret);
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

			if ($resp->isSuccess()) {

				if ($_POST['question_name'] && $_POST['question_tel']) {

					$name = $_POST['question_name'];
					$tel = $_POST['question_tel'];
					$message = $_POST['question_text'];

					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail->isSMTP();
					$mail->Host = trim($this->config->get('config_mail_smtp_hostname'));
					$mail->SMTPAuth = true;
					//$mail->SMTPDebug = 3;
					$mail->Username = trim($this->config->get('config_mail_parameter'));
					$mail->Password = trim($this->config->get('config_mail_smtp_password'));
					$mail->SMTPSecure = 'ssl';
					$mail->Port = trim($this->config->get('config_mail_smtp_port'));
					$mail->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
						)
					);
					$mail->setFrom(trim($this->config->get('config_email')), 'Almacom');

					$mail->addReplyTo(trim($this->config->get('config_mail_parameter')), 'Almacom.kz');
					
					$mails = explode(",", $this->config->get('config_mail_alert_email'));
					array_unshift($mails, $this->config->get('config_email'));
			    	for ($x = 0; $x < count($mails); $x++) {
			    		$mail->addAddress(trim($mails[$x]));
			    	}

					$mail->isHTML(true);
					$mail->Subject = 'Новый вопрос с сайта ALMACOM';
					$mail->Body =
						'Имя: <strong>' . $name . '</strong><br>' .
						'Номер телефона: <strong>' . $tel . '</strong><br>' .
						'Вопрос: <strong>' . $message . '</strong><br>' .
					'';

					if(!$mail->send()) {
						echo 'Message could not be sent.';
						echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
						echo '<div class="order_call-in order_call-success"><img src="catalog/view/theme/almacom/img/check-mark.png" alt=""><p>Вопрос успешно отправлен!</p><button class="oc-success-btn">Закрыть</button></div>';
					}
				} else {
					echo "Есть не заполненные поля в форме!";
				}
			} else {
				echo "Код капчи не прошёл проверку на сервере";
			}
		} else {
			echo "Капча не существует";
		}
    }

    public function service() {

    	session_start();

		date_default_timezone_set('Etc/UTC');

		require 'PHPMailer-5.2-stable/PHPMailerAutoload.php';
		require 'recaptcha/autoload.php';

		$secret = '6Lf5wsAaAAAAAG_yQHsd6xY-kHw4gzvEOxQ_MXNb';

		if (isset($_POST['g-recaptcha-response'])) {

			$recaptcha = new \ReCaptcha\ReCaptcha($secret);
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

			if ($resp->isSuccess()) {

				if ($_POST['service_name'] && $_POST['service_tel']) {

					$name = $_POST['service_name'];
					$tel = $_POST['service_tel'];
					$service = $_POST['service_type'];

					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail->isSMTP();
					$mail->Host = trim($this->config->get('config_mail_smtp_hostname'));
					$mail->SMTPAuth = true;
					//$mail->SMTPDebug = 3;
					$mail->Username = trim($this->config->get('config_email'));
					$mail->Password = trim($this->config->get('config_mail_smtp_password'));
					$mail->SMTPSecure = 'ssl';
					$mail->Port = trim($this->config->get('config_mail_smtp_port'));
					$mail->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
						)
					);
					$mail->setFrom(trim($this->config->get('config_email')), 'Almacom');
					$mail->addReplyTo(trim($this->config->get('config_mail_parameter')), 'Almacom.kz');
					
					$mail->addAddress(trim($this->config->get('config_email_service')));

					$mail->isHTML(true);
					$mail->Subject = 'Новый заказ на сервис с сайта ALMACOM';
					$mail->Body =
						'Имя: <strong>' . $name . '</strong><br>' .
						'Номер телефона: <strong>' . $tel . '</strong><br>' .
						'Тип сервиса: <strong>' . $service . '</strong><br>' .
					'';

					if(!$mail->send()) {
						echo 'Message could not be sent.';
						echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
						echo '<div class="order_call-in order_call-success">
								<img src="catalog/view/theme/almacom/img/check-mark.png" alt="">
								<p>Ваша заявка успешно отправлена!</p><button class="oc-success-btn">Закрыть</button>
							</div>';
					}
				} else {
					echo "Есть не заполненные поля в форме!";
				}
			} else {
				echo "Код капчи не прошёл проверку на сервере";
			}
		} else {
			echo "Капча не существует";
		}
    }
}

?>