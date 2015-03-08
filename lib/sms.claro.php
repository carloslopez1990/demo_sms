<?php
# celopez1990
# Enviar SMS Claro
# Tue Jul 15 01:37:37 2014

function enviarSMSClaro( $origen, $destino, $mensaje ) {
	$ch = curl_init( 'http://sms2ni.claro.com.sv/pages/telecomv2.aspx' );

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_REFERER, 'http://www.claro.com.ni/portal/ni/pc/personas/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");

	$info = curl_exec($ch);

	preg_match_all('|name="LBD_VCT__pages_telecomv2_enviosms21_noticaptcha" value="(.*?)" />|', $info, $matches);
	$var1_captcha = $matches[1][0];

	preg_match_all('|id=\'_pages_telecomv2_enviosms21_noticaptcha_CaptchaImage\' src=\'(.*?)\' alt=\'CAPTCHA|', $info, $matches);

	$captcha_url = urldecode(str_replace('', '', $matches[1][0]));
	$session_id = explode( '=', $captcha_url );
	$session_id = $session_id[ count( $session_id ) - 1 ];

	$captcha_url = 'http://sms2ni.claro.com.sv/('.$session_id.')/pages/'.$captcha_url;

	$rand = rand(0, 999999);
	file_put_contents( './cache/captcha'.$rand.'.wav', file_get_contents( str_replace('image', 'sound', $captcha_url) ) );
	$captcha = trim( wav2txt( './cache/captcha'.$rand.'.wav' ) );
	@unlink( './cache/captcha'.$rand.'.wav' );
	@unlink( './cache/captcha'.$rand.'.flac' );

	$cnx = fsockopen('sms2ni.claro.com.sv', 80);

	$params = "LBD_VCT__pages_telecomv2_enviosms21_noticaptcha=".$var1_captcha."&LBD_SGC__pages_telecomv2_enviosms21_noticaptcha=0&__EVENTTARGET=Enviosms21%3AbtnenviarHtml&__EVENTARGUMENT=&__VIEWSTATE=dDwtMTQ2MjM0MDE4OTt0PDtsPGk8MT47PjtsPHQ8O2w8aTwxPjs%2BO2w8dDw7bDxpPDE%2BO2k8Mz47PjtsPHQ8cDxsPFZpc2libGU7PjtsPG88dD47Pj47bDxpPDQ%2BO2k8Nz47PjtsPHQ8O2w8aTwwPjs%2BO2w8dDw7bDxpPDE%2BOz47bDx0PHA8bDxpbm5lcmh0bWw7PjtsPFxlOz4%2BOzs%2BOz4%2BOz4%2BO3Q8O2w8aTwwPjs%2BO2w8dDw7bDxpPDU%2BOz47bDx0PHA8bDxpbm5lcmh0bWw7PjtsPFxlOz4%2BOzs%2BOz4%2BOz4%2BOz4%2BO3Q8cDxsPFZpc2libGU7PjtsPG88Zj47Pj47bDxpPDA%2BOz47bDx0PDtsPGk8MD47PjtsPHQ8O2w8aTwxPjtpPDM%2BOz47bDx0PHA8bDxpbm5lcmh0bWw7PjtsPFxlOz4%2BOzs%2BO3Q8cDxwPGw8VmlzaWJsZTs%2BO2w8bzxmPjs%2BPjs%2BOzs%2BOz4%2BOz4%2BOz4%2BOz4%2BOz4%2BOz4%2BOz75%2FWR30qrL%2FVFPf1TkVN%2FHEyYGeA%3D%3D&Enviosms21%3Atxtorigen=".urlencode( $origen )."&Enviosms21%3AtxtTel=".$destino."&Enviosms21%3Atxtmsg=".urlencode( $mensaje )."&Enviosms21%3AtxtCG=".$captcha;

	$data = "POST /(".$session_id.")/pages/telecomv2.aspx HTTP/1.1\r\n".
			"Host: sms2ni.claro.com.sv\r\n".
			"Proxy-Connection: keep-alive\r\n".
			"Content-Length: ".strlen($params)."\r\n".
			"Cache-Control: max-age=0\r\n".
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n".
			"Origin: http://sms2ni.claro.com.sv\r\n".
			"User-Agent: Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36\r\n".
			"Content-Type: application/x-www-form-urlencoded\r\n".
			"Referer: http://sms2ni.claro.com.sv/(".$session_id.")/pages/telecomv2.aspx\r\n".
			"Accept-Encoding: gzip,deflate,sdch\r\n".
			"Accept-Language: es-ES,es;q=0.8,en;q=0.6,gl;q=0.4,id;q=0.2,pt;q=0.2,nb;q=0.2,fr;q=0.2\r\n\r\n".
			$params;

	if( $cnx ) {
		fwrite( $cnx, $data );

		$ret = '';
		while( !feof( $cnx ) )
			$ret .= fgets( $cnx );

		if( preg_match('|Mensaje enviado con exito|', $ret) )
			return true;
		else return false;
	}
	else return false;
}