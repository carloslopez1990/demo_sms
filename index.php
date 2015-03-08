<?php

	# Ejemplo de envío de sms utilizando webs de Claro y Movistar
	# Creado por Carlos Ernesto López
	# celopez1990.blogspot.com || facebook.com/c.ernest.1990

	include 'config.php';
	
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Env&iacute;o de SMS a toda Nicaragua</title>
		<link href="http://allfont.es/css/?fonts=28-days-later" rel="stylesheet" type="text/css" />
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/sms.css">
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<script src="//code.jquery.com/jquery.js"></script>
		<script src="js/jquery.form.js"></script>
		<script type="text/javascript" src="js/jquery.fittext.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script src="js/bootbox.min.js"></script>

		<script src="js/app.js"></script>
		<div id="topheader"></div>

		<div class="container" id="main">
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<div id="home_title">Enviar&nbsp;Chat</div>

					<ul id="features" class="hidden-xs">
						<li>Envía Mensajes a toda Nicaragua desde un único formulario</li>
						<li>No es necesario que te registres ni que ingreses códigos de verificación</li>
						<li>Identificación automática de operadora</li>
					</ul>
				</div>

				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
					<form action="ajax.php?d=enviarSMS" method="POST" role="form" spellcheck="false" id="form">	
						<div class="form-group">
							<label for="origen">Origen</label>
							<input type="text" maxlength="10" class="form-control" id="origen" placeholder="Origen" name="origen" required="required" autofocus>
						</div>

						<div class="form-group">
							<label for="destino">Destino</label>
							<input type="number" class="form-control" id="destino" placeholder="Destino" name="destino" required="required">
						</div>

						<div class="form-group">
							<label for="operadora">Operadora</label>
							<select name="operadora" id="operadora" class="form-control" required="required">
								<option value="C">Claro</option>
								<option value="M">Movistar</option>
							</select>
						</div>

						<div class="form-group">
							<label for="mensaje">Mensaje</label>
							<textarea maxlength="90" class="form-control" id="mensaje" placeholder="Mensaje" name="mensaje" style="height: 90px" required="required"></textarea>
							<div class="text-right">
								<span id="msgcounter">0</span> / 90
							</div>
						</div>

						<div class="text-center">
							<button id="sub" type="button" onclick="_submit()" class="btn btn-danger btn-large">Enviar Mensaje</button>
							<div id="enviando" style="display: none">Enviando...</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>