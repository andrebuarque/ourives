var controlador = BASE + '/cadastroProduto/';
var tabs = $('#tabs');
var formCadastro = Form('#form-cadastro');
var message = Message('#message');
var modalExcluir = $('#modalExcluir');
var confirmaExclusao = $('#confirmaExclusao');

$(function(){
	
	
	
	modalExcluir.modal('hide');
	
	var tabela_listagem = $('#tabela-listagem').dataTable({
		'aoColumns': [
		     {sTitle: 'ID', bVisible: false},
		     {sTitle: 'Nome'},
		     {sTitle: 'Valor', sWidth: '200px'},
		     {sTitle: 'Ações', sClass: 'center',  sWidth: '80px', bSortable: false}
		],
        "aaSorting": [
            [0, 'desc']
        ],
        "sAjaxSource": controlador + "listar"
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
				tabela_listagem.fnReloadAjax(controlador + "listar");
				message.success(retorno.conteudo);
			} else {
				message.error(retorno.conteudo);
			}
			
			Util.toTop();
			
		}, 'json');
	});
	
	$('a[href="#cadastro"]').click(function() {
		formCadastro.enable();
		formCadastro.clear();
		$(".autosize").trigger('autosize.resize');
	});
	
	formCadastro.self.validate({
		messages: {
			nome: {
				required: "Campo obrigatório."
			},
			valor: {
				required: "Campo obrigatório.",
				number: "O campo só aceitar numeros"
			}
		},
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				url: controlador + "cadastrar",
				dataType: 'json',
				type: 'POST',
				success: function(retorno){
					if (retorno.status == true) {
						tabela_listagem.fnReloadAjax(controlador + "listar");
						tabs.find('a[href="#listagem"]').tab('show');
						message.success(retorno.conteudo);
						formCadastro.clear();
					} else {
						message.error(retorno.conteudo);
					}
					Util.toTop();
				}
			});
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
    	{id: 'descricao', type: FormType.TYPE_TEXTAREA, value: registro.descricao},
    	{id: 'valor', type: FormType.TYPE_TEXT, value: registro.valor}
    ];
	
	formCadastro.preencher(camposForm);
};