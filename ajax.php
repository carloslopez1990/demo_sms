<?php
# Ejemplo de envío de sms utilizando webs de Claro y Movistar
# Creado por Carlos Ernesto López
# celopez1990.blogspot.com || facebook.com/c.ernest.1990
	
include 'config.php';
include 'lib/wav2txt.php';

set_time_limit(0);

switch( $_GET['d'] ) {
	case 'enviarSMS':
		if( !in_array($_POST['operadora'], array('M', 'C')) )
			exit( 'Operadora inv&aacute;lida' );

		# validar destino
		$_POST['destino'] = (int) $_POST['destino'];
		if( strlen( $_POST['destino'] ) != 8 )
			exit( 'El destino debe ser de 8 digitos' );

		# validar origen
		if( strlen( $_POST['origen'] ) > 10 )
			$_POST['origen'] = substr( $_POST['origen'], 0, 10 );

		# validar mensaje	
		if( strlen( $_POST['mensaje'] ) > 90 )
			exit( 'El mensaje solo puede contener 90 caracteres' );

		if( $_POST['operadora'] == 'M' ) {
			include 'lib/sms.movistar.php';

			# agregar origen al mensaje
			$_POST['mensaje'] = $_POST['origen'].': '.$_POST['mensaje'];

			if( enviarSMSMovistar( $_POST['destino'], $_POST['mensaje'] ) )
				print 1;
			else if( enviarSMSMovistar( $_POST['destino'], $_POST['mensaje'] ) )
				print 1;
			else 
				print 0;
		}
		else if( $_POST['operadora'] == 'C' ) {
			include 'lib/sms.claro.php';

			if( enviarSMSClaro( $_POST['origen'], $_POST['destino'], $_POST['mensaje'] ) )
				print 1;
			else if( enviarSMSClaro( $_POST['origen'], $_POST['destino'], $_POST['mensaje'] ) )
				print 1;
			else 
				print 0;

		}
	break;

	case 'getOperadora':
		$prefijos_claro = explode(', ', '222, 231, 550, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 581, 586, 820, 821, 822, 823, 833, 835, 836, 840, 841, 842, 843, 844, 849, 850, 851, 852, 853, 854, 860, 861, 862, 863, 864, 865, 866, 869, 870, 871, 872, 873, 874, 882, 883, 884, 885, 890, 891, 892, 893, 894');
		$prefijos_movistar = explode(', ', '750, 770, 771, 772, 773, 774, 775, 778, 780, 781, 782, 783, 784, 785, 786, 787, 788, 789, 810, 811, 812, 813, 814, 815, 816, 817, 818, 819, 824, 825, 826, 827, 828, 829, 832, 837, 838, 839, 845, 846, 847, 848, 855, 856, 857, 858, 859, 867, 868, 875, 876, 877, 878, 879, 880, 881, 886, 887, 888, 889, 895, 896, 897, 898, 899');

		$_GET['destino'] = (int) $_GET['destino'];

		if( strlen( $_GET['destino'] ) != 8 )
			exit( 'I' );
		
		$prefijo = substr( $_GET['destino'], 0, 3 );

		if( in_array($prefijo, $prefijos_claro) )
			exit( 'C' );
		else if( in_array($prefijo, $prefijos_movistar) )
			exit( 'M' );
		else exit( 'I' );
	break;
}