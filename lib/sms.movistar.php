<?php
# celopez1990
# Enviar SMS Movistar
# Thu Jun 26 17:55:15 2014

function curl( $url, $fields = array(), $initsession = false, $referer = '') {
	$ch = curl_init( $url );

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	if( $referer == '' )
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	else 
		curl_setopt($ch, CURLOPT_REFERER, $referer);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSLVERSION, 1); 
	
	if( $initsession )
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);

	curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
	
	if(  count($fields  ) > 0  ) {	
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	}

	return curl_exec($ch);
}

function enviarSMSMovistar( $destino, $mensaje ) {
	$loginurl = 'https://www2.movistar.com.ni/mhome/login.aspx';

	$login_get_info = curl( $loginurl, array(), true );

	preg_match_all( '|<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="(.*?)" />|', $login_get_info, $matches );
	$__EVENTVALIDATION = $matches[1][0];

	preg_match_all( '|<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*?)" />|', $login_get_info, $matches );
	$__VIEWSTATE = $matches[1][0];

	preg_match_all( '|<input type="hidden" name="__PREVIOUSPAGE" id="__PREVIOUSPAGE" value="(.*?)" />|', $login_get_info, $matches );
	$__PREVIOUSPAGE = $matches[1][0];

	$data = array(
				'ctl00$ctl00$ContentPlaceHolder1$ContentPlaceHolderInterno$txtEMail' => MOV_EMAIL,
				'ctl00$ctl00$ContentPlaceHolder1$ContentPlaceHolderInterno$txtClave' => MOV_PASS,
				'ctl00$ctl00$ContentPlaceHolder1$ContentPlaceHolderInterno$btnEnviar' => '',
				'__EVENTVALIDATION' => $__EVENTVALIDATION,
				'__VIEWSTATE' => $__VIEWSTATE,
				'__PREVIOUSPAGE' => $__PREVIOUSPAGE,
			);

	curl( $loginurl, $data, false );

	$envio_url = 'https://www2.movistar.com.ni/Personas/Default.aspx';

	$res = curl( $envio_url, array(), false, 'https://www2.movistar.com.ni/Personas/Default.aspx' );

	preg_match_all( '|<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="(.*?)" />|', $res, $matches );
	$__EVENTVALIDATION = $matches[1][0];

	preg_match_all( '|<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*?)" />|', $res, $matches );
	$__VIEWSTATE = $matches[1][0];

	$data = array(
			'ctl00$ContentPlaceHolder1$wucContenidoDestacado$wucEnviarSMS$txtNoMovil' => $destino,
			'ctl00$ContentPlaceHolder1$wucContenidoDestacado$wucEnviarSMS$tweNumMovil_ClientState' => '',
			'ctl00$ContentPlaceHolder1$wucContenidoDestacado$wucEnviarSMS$txtMensaje' => $mensaje,
			'ctl00$ContentPlaceHolder1$wucContenidoDestacado$wucEnviarSMS$txtNombre' => MOV_NAME,
			'ctl00$ContentPlaceHolder1$wucContenidoDestacado$wucEnviarSMS$btnEviarSms' => '',
			'__EVENTVALIDATION' => $__EVENTVALIDATION,
			'__VIEWSTATE' => $__VIEWSTATE
		);


	$info = curl( $envio_url, $data, false, 'https://www2.movistar.com.ni/Personas/Default.aspx' );

	if( preg_match('|Mensaje enviado|', $info) )
		return true;

	return false;
}
