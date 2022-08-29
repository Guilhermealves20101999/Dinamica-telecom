<?
// STATUS: PROCESSANDO
function WCProcessandoAPI( $order_id ){
	
	date_default_timezone_set('America/Sao_Paulo');
	$hora = date('H'); // Pega a hora atual

	$saudacao = array(
		'Boa madrugada',
		'Bom dia',
		'Boa tarde',
		'Boa noite'
	);

	$despedida = array(
		'Até logo',
		'Até mais',
		'Até mais tarde'
	);
	
	$order = wc_get_order( $order_id );
	$data = $order->get_data();
	$nome = $data['billing']['first_name']; // Variável do Nome do cliente
	$phone = "55" . preg_replace("/[^0-9]/", "", $data['billing']['phone']); // Variável do Telefone do cliente
	
	$msgsaudacao = $saudacao[($hora/6)]; // Escolhe uma das mensagens de saudação de acordo com a hora
	$msgdespedida = $despedida[(rand(0,2))]; // Escolhe um item aleatório da array de despedida
	$msg = "_" . $msgsaudacao . " " . $nome . "_, seu pedido esta com status: *Processando*. " . $msgdespedida;

	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://apisac.pypress.com.br/api/messages/send", //URL do backend do Whaticket
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{ \"number\":\"$phone\", \"body\":\"$msg\" }",
	  CURLOPT_HTTPHEADER => [
		"Authorization: Bearer a8a8a589-6db6-4438-a2b2-fa514127dd02", //Token da API do Whaticket
		"Content-Type: application/json"
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	} 
}

add_action( 'woocommerce_order_status_processing', 'WCProcessandoAPI');

// STATUS: FALHA 
function WCFalhaAPI( $order_id ){
	
	date_default_timezone_set('America/Sao_Paulo');
	$hora = date('H'); // Pega a hora atual

	$saudacao = array(
		'Boa madrugada',
		'Bom dia',
		'Boa tarde',
		'Boa noite'
	);

	$despedida = array(
		'Até logo',
		'Até mais',
		'Até mais tarde'
	);
	
	$order = wc_get_order( $order_id );
	$data = $order->get_data();
	$nome = $data['billing']['first_name']; // Variável do Nome do cliente
	$phone = "55" . preg_replace("/[^0-9]/", "", $data['billing']['phone']); // Variável do Telefone do cliente
	
	$msgsaudacao = $saudacao[($hora/6)]; // Escolhe uma das mensagens de saudação de acordo com a hora
	$msgdespedida = $despedida[(rand(0,2))]; // Escolhe um item aleatório da array de despedida
	$msg = "_" . $msgsaudacao . " " . $nome . "_, seu pedido esta com status: *Falha*. " . $msgdespedida;

	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://apisac.pypress.com.br/api/messages/send", //URL do backend do Whaticket
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{ \"number\":\"$phone\", \"body\":\"$msg\" }",
	  CURLOPT_HTTPHEADER => [
		"Authorization: Bearer a8a8a589-6db6-4438-a2b2-fa514127dd02", //Token da API do Whaticket
		"Content-Type: application/json"
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	} 
}
add_action( 'woocommerce_order_status_failed', 'WCFalhaAPI');

// STATUS: AGUARDANDO
function WCAguardandoAPI( $order_id ){
	
	date_default_timezone_set('America/Sao_Paulo');
	$hora = date('H'); // Pega a hora atual

	$saudacao = array(
		'Boa madrugada',
		'Bom dia',
		'Boa tarde',
		'Boa noite'
	);

	$despedida = array(
		'Até logo',
		'Até mais',
		'Até mais tarde'
	);
	
	$order = wc_get_order( $order_id );
	$data = $order->get_data();
	$nome = $data['billing']['first_name']; // Variável do Nome do cliente
	$phone = "55" . preg_replace("/[^0-9]/", "", $data['billing']['phone']); // Variável do Telefone do cliente
	
	$msgsaudacao = $saudacao[($hora/6)]; // Escolhe uma das mensagens de saudação de acordo com a hora
	$msgdespedida = $despedida[(rand(0,2))]; // Escolhe um item aleatório da array de despedida
	$msg = "_" . $msgsaudacao . " " . $nome . "_, seu pedido esta com status: *Aguardando*. " . $msgdespedida;

	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://apisac.pypress.com.br/api/messages/send", //URL do backend do Whaticket
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{ \"number\":\"$phone\", \"body\":\"$msg\" }",
	  CURLOPT_HTTPHEADER => [
		"Authorization: Bearer a8a8a589-6db6-4438-a2b2-fa514127dd02", //Token da API do Whaticket
		"Content-Type: application/json"
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	} 
}
add_action( 'woocommerce_order_status_on-hold', 'WCAguardandoAPI');

// STATUS: REEMBOLSADO
function WCReembolsadoAPI( $order_id ){
	
	date_default_timezone_set('America/Sao_Paulo');
	$hora = date('H'); // Pega a hora atual

	$saudacao = array(
		'Boa madrugada',
		'Bom dia',
		'Boa tarde',
		'Boa noite'
	);

	$despedida = array(
		'Até logo',
		'Até mais',
		'Até mais tarde'
	);
	
	$order = wc_get_order( $order_id );
	$data = $order->get_data();
	$nome = $data['billing']['first_name']; // Variável do Nome do cliente
	$phone = "55" . preg_replace("/[^0-9]/", "", $data['billing']['phone']); // Variável do Telefone do cliente
	
	$msgsaudacao = $saudacao[($hora/6)]; // Escolhe uma das mensagens de saudação de acordo com a hora
	$msgdespedida = $despedida[(rand(0,2))]; // Escolhe um item aleatório da array de despedida
	$msg = "_" . $msgsaudacao . " " . $nome . "_, seu pedido esta com status: *Reembolsado*. " . $msgdespedida;

	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://apisac.pypress.com.br/api/messages/send", //URL do backend do Whaticket
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{ \"number\":\"$phone\", \"body\":\"$msg\" }",
	  CURLOPT_HTTPHEADER => [
		"Authorization: Bearer a8a8a589-6db6-4438-a2b2-fa514127dd02", //Token da API do Whaticket
		"Content-Type: application/json"
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	} 
}
add_action( 'woocommerce_order_status_refunded', 'WCReembolsadoAPI');

// STATUS: CANCELADO
function WCCanceladoAPI( $order_id ){
	
	date_default_timezone_set('America/Sao_Paulo');
	$hora = date('H'); // Pega a hora atual

	$saudacao = array(
		'Boa madrugada',
		'Bom dia',
		'Boa tarde',
		'Boa noite'
	);

	$despedida = array(
		'Até logo',
		'Até mais',
		'Até mais tarde'
	);
	
	$order = wc_get_order( $order_id );
	$data = $order->get_data();
	$nome = $data['billing']['first_name']; // Variável do Nome do cliente
	$phone = "55" . preg_replace("/[^0-9]/", "", $data['billing']['phone']); // Variável do Telefone do cliente
	
	$msgsaudacao = $saudacao[($hora/6)]; // Escolhe uma das mensagens de saudação de acordo com a hora
	$msgdespedida = $despedida[(rand(0,2))]; // Escolhe um item aleatório da array de despedida
	$msg = "_" . $msgsaudacao . " " . $nome . "_, seu pedido esta com status: *Cancelado*. " . $msgdespedida;

	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://leadpy.online.com.br/api/messages/send", //URL do backend do Whaticket
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{ \"number\":\"$phone\", \"body\":\"$msg\" }",
	  CURLOPT_HTTPHEADER => [
		"Authorization: Bearer a8a8a589-6db6-4438-a2b2-fa514127dd02", //Token da API do Whaticket
		"Content-Type: application/json"
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	} 
}
add_action( 'woocommerce_order_status_cancelled', 'WCCanceladoAPI');