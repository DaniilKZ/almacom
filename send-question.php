<?php

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
			$mail->Host = 'almacom.adex.kz';
			$mail->SMTPAuth = true;
			//$mail->SMTPDebug = 3;
			$mail->Username = 'test@adex.kz';
			$mail->Password = '5fe2f7L~';
			$mail->SMTPSecure = 'tls';
			$mail->Port = 25;
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			$mail->setFrom('test@adex.kz', 'Almacom');
			$mail->addAddress('otepms@gmail.com');
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

?>