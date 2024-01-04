<?php 

	define('TOKEN_FILE', __DIR__.'/token_info.json');

	$subdomain = 'almacom'; 
	$client_id_var = "c06b2cd6-14a7-43a7-96f3-069f6aa3b1fd";
	$client_secret_var = "e09fdxT7lZc1JORIpc5PRcEJMXEVaHTUnGdqkGzAN6ZUvznHSfMfdBWacY8OBJXq";
	$code_var = "";
	$redirect_uri_var = "https://almacom.adex.kz/index.php?route=mail/callback/";

	$newAcces = '';

	$shipping_address = (!empty($shipping_address)) ? $shipping_address : '';
	$message = (!empty($message)) ? $message : '';
	$amoCrmProduct = (!empty($amoCrmProduct)) ? $amoCrmProduct : '';
	$amoCrmOrderCheck = (!empty($amoCrmOrderCheck)) ? $amoCrmOrderCheck : '';
	$amoCrmProductTotal = (!empty($amoCrmProductTotal)) ? $amoCrmProductTotal : '';

	$paymentmethod = ( !empty($paymentmethod) ) ? $paymentmethod : '';
	$numberorder = ( !empty($numberorder) ) ? $numberorder : '';
	$dateadded = ( !empty($dateadded) ) ? $dateadded : ''; 
 

	$pageDocument = @file_get_contents(TOKEN_FILE);

	function crateLeads($shipping_address = "", $paymentmethod = "", $numberorder = "", $dateadded = "", $amoCrmProductTotal = "", $amoCrmOrderCheck = false,  $amoCrmProduct = "", $message = "", $tel, $name, $subdomain = '', $accessToken = ''){ 

		$link = 'https://' . $subdomain.'.amocrm.ru/private/api/v2/json/accounts/current';

		$headers = [
			'Authorization: Bearer ' . $accessToken ,
			'Content-Type: application/json'
		];

		$curl = curl_init(); 
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
		curl_setopt($curl,CURLOPT_URL, $link);
		curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl,CURLOPT_HEADER, false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
		$auth = curl_exec($curl); 

		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		$code = (int)$code;
		$errors = [
			400 => 'Bad request',
			401 => 'Unauthorized',
			403 => 'Forbidden',
			404 => 'Not found',
			500 => 'Internal server error',
			502 => 'Bad gateway',
			503 => 'Service unavailable',
		];

		try
		{
			/** Если код ответа не успешный - возвращаем сообщение об ошибке  */
			if ($code < 200 || $code > 204) {
				throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
			}else{

				if ( !empty(json_decode($auth)) && isset(json_decode($auth)->response->account->name) ) {  
					include_once  '/var/www/vhosts/almacom.kz/httpdocs/AmoCRM/contact_add.php';
				}
			}
		}
		catch(\Exception $e)
		{
			die('Ошибка СОЗДАНИЯ ЛИДА: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
		}
	}

	function createAccesToken($subdomain = "", $client_id = "", $client_secret = "", $code = "", $redirect_uri = ""){
		$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; 
		$data = [
			'code' => $code,
			'client_id' => $client_id,
			'redirect_uri' => $redirect_uri,
			'client_secret' => $client_secret,
			'grant_type' => 'authorization_code',
		];

		$curl = curl_init(); 
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
		curl_setopt($curl,CURLOPT_URL, $link);
		curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
		curl_setopt($curl,CURLOPT_HEADER, false);
		curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
				$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
				$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				curl_close($curl);

				$code = (int)$code;
				$errors = [
					400 => 'Bad request',
					401 => 'Unauthorized',
					403 => 'Forbidden',
					404 => 'Not found',
					500 => 'Internal server error',
					502 => 'Bad gateway',
					503 => 'Service unavailable',
				];

				try
				{
					/** Если код ответа не успешный - возвращаем сообщение об ошибке  */
					if ($code < 200 || $code > 204) {
						throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
					}
				}
				catch(\Exception $e)
				{
					die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
				}

				$response = json_decode($out, true);

				$dataSave = [
					'token_type' => $response['token_type'],
					'expires_in' => $response['expires_in'],
					'access_token' => $response['access_token'],
					'refresh_token' => $response['refresh_token'],
				];

				file_put_contents(TOKEN_FILE, json_encode($dataSave));
	}

	function updateAccesToken($shipping_address = "", $paymentmethod = "", $numberorder = "", $dateadded = "", $amoCrmProductTotal = "",$amoCrmOrderCheck = false,  $amoCrmProduct = "", $message = "", $tel, $name, $subdomain = "", $client_id = "", $client_secret = "", $refresh_token = "", $redirect_uri = ""){
		$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token';
		$data = [
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'grant_type' => 'refresh_token',
			'refresh_token' => $refresh_token,
			'redirect_uri' => $redirect_uri,
		];

		$curl = curl_init();
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
		curl_setopt($curl,CURLOPT_URL, $link);
		curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
		curl_setopt($curl,CURLOPT_HEADER, false);
		curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
		$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		$code = (int)$code;
		$errors = [
			400 => 'Bad request',
			401 => 'Unauthorized',
			403 => 'Forbidden',
			404 => 'Not found',
			500 => 'Internal server error',
			502 => 'Bad gateway',
			503 => 'Service unavailable',
		];

		try
		{
			if ($code < 200 || $code > 204) {
				throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
			}
		}
		catch(\Exception $e)
		{
			die('Ошибка ОБНОВЛЕНИЯ: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
		}


		$response = json_decode($out, true);

		$dataSave = [
			'token_type' => $response['token_type'],
			'expires_in' => $response['expires_in'],
			'access_token' => $response['access_token'],
			'refresh_token' => $response['refresh_token'],
		];

		file_put_contents(TOKEN_FILE, json_encode($dataSave));

		crateLeads($shipping_address, $paymentmethod, $numberorder, $dateadded, $amoCrmProductTotal, $amoCrmOrderCheck, $amoCrmProduct, $message, $tel, $name, $subdomain, $response['access_token']);
	}

	if ($pageDocument === false) {
	    echo "Не существует файла!";
	}else{
		$accessToken = json_decode(file_get_contents(TOKEN_FILE), true);
		if (
			isset($accessToken)
			&& isset($accessToken['token_type'])
			&& isset($accessToken['expires_in'])
			&& isset($accessToken['access_token'])
			&& isset($accessToken['refresh_token'])
		) {
			// "Что то есть";
			updateAccesToken($shipping_address, $paymentmethod, $numberorder, $dateadded, $amoCrmProductTotal, $amoCrmOrderCheck,  $amoCrmProduct, $message, $tel, $name, $subdomain, $client_id_var, $client_secret_var, $accessToken['refresh_token'], $redirect_uri_var);
		}else{
			createAccesToken($subdomain, $client_id_var, $client_secret_var, $code_var, $redirect_uri_var); //Используется единожды при запуске нового кода
		}
} 