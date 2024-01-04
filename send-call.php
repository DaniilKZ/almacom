<?php

date_default_timezone_set('Etc/UTC');

require 'PHPMailer-5.2-stable/PHPMailerAutoload.php';

if ($_POST['name'] && $_POST['tel']) {

	$name = $_POST['name'];
	$tel = $_POST['tel'];

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

?>