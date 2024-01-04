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

		if ($_POST['your_name'] && $_POST['your_phone']) {

			$name = $_POST['your_name'];
			$tel = $_POST['your_phone'];
			$message = $_POST['your_message'];

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

?>