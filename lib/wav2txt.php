<?php
# Ejemplo de envío de sms utilizando webs de Claro y Movistar
# Creado por Carlos Ernesto López
# celopez1990.blogspot.com || facebook.com/c.ernest.1990

function wav2txt( $path ) {
	shell_exec( 'ffmpeg -i '.$path.' -ar 44100 '.str_replace('.wav', '.flac', $path) );
	$ch = curl_init('https://www.google.com/speech-api/v2/recognize?output=json&lang=en-uk&key=AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: audio/x-flac; rate=44100;'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSLVERSION, 3); 
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => '@'.realpath(str_replace('.wav', '.flac', $path)))); 
	$response = curl_exec($ch);
	$response = json_decode(trim(str_replace( '{"result":[]}', '', $response )));
	$n = count( $response->result[0]->alternative );
	foreach( $response->result[0]->alternative as $res ) {
		$res = str_replace( ' ', '', $res->transcript );
		if( strlen( $res ) == 5 ) {
			return $res;
		}
	}
}