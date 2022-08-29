<?
add_action( 'elementor_pro/forms/new_record', function( $record, $handler ) {
    
	$form_name = $record->get_form_settings( 'form_name' );
	
    if ( 'frmapi' !== $form_name ) { //Colocar o nome do formulário
        return;
    }
	
	$raw_fields = $record->get( 'fields' );
    $fields = [];
    foreach ( $raw_fields as $id => $field ) {
        $fields[ $id ] = $field['value'];
    }
	
	$mensagem = $fields['mensagem']; //Colocar o ID do campo do formulário
	$telefoneForm = preg_replace("/[^0-9]/", "", $fields['telefone']); //Colocar o ID do campo do formulário
	$telefone1 = substr($telefoneForm, 0, 2);
	$telefone2 = substr($telefoneForm, -8);
	$telefone = '55' . $telefone1 . $telefone2;
	
	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://leadpy.online.com.br/api/messages/send", //URL do backend do Whaticket
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{ \"number\":\"$telefone\", \"body\":\"$mensagem\" }",
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

}, 10, 2 );