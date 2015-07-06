var message = Message('#message');

$(function(){
	
	$("#formLogin").validate({
		rules: {
		    email: {
		      required: true,
		      email: true
		    },
		    senha: "required"
		},
		messages: {
			email: {
				email: "E-mail inválido.",
				required: "Informe o e-mail."
			},
			senha: {
				required: "Informe a senha."
			}
		},
		submitHandler: function(form) {
			$('#btLogin').hide();
			$('#btCarregando').show();
			
			$.post(BASE + '/login/autenticar', $(form).serialize(), function(retorno) {
				if (retorno.status == true) {
					location.href = retorno.conteudo;
				} else {
					message.error(retorno.conteudo);
				}
				
				$('#btLogin').show();
				$('#btCarregando').hide();
				
			}, 'json');
		}
	});
	
	$('#esqueciSenha').click(function(){
		var email = $('#email').val().trim();
		
		if (!email) {
			message.error('Informe o e-mail.');
			return false;
		}
		
		$.post(BASE + '/login/esqueciMinhaSenha', $('#formLogin').serialize(), function(retorno) {
			if (retorno.status == true) {
				message.success(retorno.conteudo);
			} else {
				message.error(retorno.conteudo);
			}
			
		}, 'json');
		
	});
});