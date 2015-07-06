var controlador = BASE + '/MeusDadosCadastrais/';
var tabs = $('#tabs');
var formCadastro = $('#form-cadastro');
var message = Message('#message');

$(function(){
	
	var senhaAtual = $('#senhaAtual');
	var novaSenha = $('#novaSenha');
	var confirmarNovaSenha = $('#confirmarNovaSenha');
	
	formCadastro.validate({
		rules: {
			nome: {
				required: true
			},
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			nome: {
				required: 'Campo obrigatório'
			},
			email: {
				required: 'Campo obrigatório',
				email: 'Favor preencher um e-mail válido'
			}
		},
		submitHandler: function(form){
			$.post(controlador + "alterarDadosCadastrais", $(form).serialize(), function(retorno) {
				if (retorno.status == true) {
					message.success(retorno.conteudo);
					senhaAtual.val('');
					novaSenha.val('');
					confirmarNovaSenha.val('');
					$("#divAlterarSenha").hide();
				} else {
					message.error(retorno.conteudo);
				}
				
				Util.toTop();
				
			}, 'json');
		}
	});
	
	$("#alterarSenha").toggle(function(){
		$("#divAlterarSenha").show();
	},function(){
		$("#divAlterarSenha").hide();
	});
	
});