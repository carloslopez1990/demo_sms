// Ejemplo de envío de sms utilizando webs de Claro y Movistar
// Creado por Carlos Ernesto López
// celopez1990.blogspot.com || facebook.com/c.ernest.1990

$(function(){
	$("#home_title").fitText(0.5);

	$('#destino').change(function(){
		$.get( 'ajax.php?d=getOperadora&destino=' + $(this).val(), null, function(res){
			if( res == 'I' )
				bootbox.alert( 'El destino es inválido' );
			else {
				$('#operadora option').removeAttr('selected');
				$('#operadora option[value="' + res + '"]').attr('selected', 'selected');
			}
		})
	})

	$('#mensaje').bind( 'change blur keyup', function(){
		$('#msgcounter').html( $(this).val().length )
	} )

	$('#form').ajaxForm(function( res ){
		if( res == 1 ) {
			$('#msgcounter').html(0);
			bootbox.alert( 'El mensaje fue enviado correctamente' );
		}
		else bootbox.alert( 'Ocurrió un error. Por favor inténtelo de nuevo' );

		$('#mensaje').val('');
		$('#sub').show();
		$('#enviando').hide();
	})
})

function _submit() {
	if( $('#destino').val().length != 8 )
		bootbox.alert( 'El desino es inv&aacute;lido' );
	else {
		$('#sub').hide();
		$('#enviando').show();
		$('#form').submit()
	}
}