var controlador = BASE + '/cadastroCliente/';
var tabs = $('#tabs');
var formCadastro = Form('#form-cadastro');
var message = Message('#message');
var modalExcluir = $('#modalExcluir');
var confirmaExclusao = $('#confirmaExclusao');

$(function(){
	
	modalExcluir.modal('hide');
	
	$('#cpf').mask('999.999.999-99');
	$('[id^="tel"]').mask('(99) 9999-9999');
	$('#cep').mask('99999-999');
	
	var tabela_listagem = $('#tabela-listagem').dataTable({
		'aoColumns': [
		     {sTitle: 'ID', bVisible: false},
		     {sTitle: 'Nome'},
		     {sTitle: 'CPF', sWidth: '350px'},
		     {sTitle: 'Telefones', sWidth: '200px'},
		     {sTitle: 'Ações', sClass: 'center',  sWidth: '80px', bSortable: false}
		],
        "aaSorting": [
            [0, 'desc']
        ],
        "sAjaxSource": controlador + "listarClientes"
    });
	
	$('a[data-acao="visualizar"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		};
		visualizar(params);
	});
	
	$('a[data-acao="editar"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		}; 
		editar(params);
	});
	
	$('a[data-acao="excluir"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		};
		excluir(params);
	});
	
	$('#btnCancelar').click(function(){
		tabs.find('a[href="#listagem"]').tab('show');
		formCadastro.clear();
	});
	
	confirmaExclusao.click(function(){
		var params = JSON.parse($(this).attr('params'));
		$.post(params.url, {id: params.id}, function(retorno) {
			if (retorno.status == true) {
				tabela_listagem.fnReloadAjax(controlador + "listarClientes");
				message.success(retorno.conteudo);
			} else {
				message.error(retorno.conteudo);
			}
			
			Util.toTop();
			
		}, 'json');
	});
	
	$("#buscarEndereco").click(function(){
		$(this).hide();
		$('#loader').show();
		$.post(controlador + "buscarEndereco", {cep: $("#cep").val().trim()}, function(retorno) {
			if (retorno.status == true) {
				var endereco = retorno.conteudo;
				var camposForm = [
	              	{id: 'logradouro', type: FormType.TYPE_TEXT, value: endereco.logradouro},
	              	{id: 'bairro', type: FormType.TYPE_TEXT, value: endereco.bairro},
	              	{id: 'cidade', type: FormType.TYPE_TEXT, value: endereco.cidade},
	              	{id: 'estado', type: FormType.TYPE_TEXT, value: endereco.estado}
	            ];
	          	
	          	formCadastro.preencher(camposForm);
			} else {
				message.error(retorno.conteudo);
				Util.toTop();
			}
			
			$('#buscarEndereco').show();
			$('#loader').hide();
			
		}, 'json');
	});
	
	$('a[href="#cadastro"]').click(function() {
		formCadastro.enable();
		formCadastro.clear();
	});
	
	formCadastro.self.validate({
		rules:{
			nome:{
				required: true
			},
			cpf:{
				required: true
			}
		},
		messages: {
			nome: {
				required: "Campo obrigatório."
			},
			cpf: {
				required: "Campo obrigatório."
			}
		},
		submitHandler: function(form) {
			$.post(controlador + "cadastrar", $(form).serialize(), function(retorno) {
				if (retorno.status == true) {
					tabela_listagem.fnReloadAjax(controlador + "listarClientes");
					tabs.find('a[href="#listagem"]').tab('show');
					message.success(retorno.conteudo);
					formCadastro.clear();
				} else {
					message.error(retorno.conteudo);
				}
				
				Util.toTop();
				
			}, 'json');
		}
	});
});

/**
 * params: {id, url}
 */
var visualizar = function(params) {
	$.post(params.url, {id: params.id}, function(retorno) {
		if (retorno.status == true) {
			formCadastro.disable();
			tabs.find('a[href="#cadastro"]').tab('show');
			formCadastro.self.find('#btnSalvar, #btnCancelar').hide();
			
			preencherForm(retorno.conteudo);
		} else {
			message.error(retorno.conteudo);
		}
	}, 'json');
};

/**
 * params: {id, url}
 */
var editar = function(params) {
	$.post(params.url, {id: params.id}, function(retorno) {
		if (retorno.status == true) {
			formCadastro.enable();
			formCadastro.self.find('#btnSalvar, #btnCancelar').show();
			tabs.find('a[href="#cadastro"]').tab('show');
			preencherForm(retorno.conteudo);
		} else {
			message.error(retorno.conteudo);
		}
	}, 'json');
};

/**
 * params: {id, url}
 */
var excluir = function(params) {
	confirmaExclusao.attr('params', JSON.stringify(params));
	modalExcluir.modal('show');
};

var preencherForm = function(registro) {
	formCadastro.clear();
	
	var camposForm = [
    	{id: 'id', type: FormType.TYPE_HIDDEN, value: registro.id},
    	{id: 'nome', type: FormType.TYPE_TEXT, value: registro.nome},
    	{id: 'cpf', type: FormType.TYPE_TEXT, value: registro.cpf},
    	{id: 'email', type: FormType.TYPE_TEXT, value: registro.email},
    	{id: 'telcelular', type: FormType.TYPE_TEXT, value: registro.telCelular},
    	{id: 'telresidencial', type: FormType.TYPE_TEXT, value: registro.telResidencial},
    	{id: 'telcomercial', type: FormType.TYPE_TEXT, value: registro.telComercial},
    	{id: 'cep', type: FormType.TYPE_TEXT, value: registro.endereco.cep},
    	{id: 'logradouro', type: FormType.TYPE_TEXT, value: registro.endereco.logradouro},
    	{id: 'numero', type: FormType.TYPE_TEXT, value: registro.endereco.numero},
    	{id: 'complemento', type: FormType.TYPE_TEXT, value: registro.endereco.complemento},
    	{id: 'bairro', type: FormType.TYPE_TEXT, value: registro.endereco.bairro},
    	{id: 'cidade', type: FormType.TYPE_TEXT, value: registro.endereco.cidade},
    	{id: 'estado', type: FormType.TYPE_TEXT, value: registro.endereco.estado}
    ];
	
	formCadastro.preencher(camposForm);
};