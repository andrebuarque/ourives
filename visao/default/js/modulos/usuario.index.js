
var controlador = BASE + '/CadastroUsuario/';
var tabs = $('#tabs');
var formCadastro = Form('#form-cadastro');
var message = Message('#message');
var modalExcluir = $('#modalExcluir');
var confirmaExclusao = $('#confirmaExclusao');

$('document').ready(function(){

	modalExcluir.modal('hide');
	
	formCadastro.self.validate({
		rules: {
			perfil: {
				required: true
			},
			nome: {
				required: true
			},
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			perfil: {
				required: 'Favor selecionar o perfil'
			},
			nome: {
				required: 'Favor preencher o nome do usuário'
			},
			email: {
				required: 'Favor preencher o e-mail do usuário',
				email: 'Favor preencher um e-mail válido'
			}
		},
		submitHandler: function(form){
			$(form).ajaxSubmit({
				url: controlador + "cadastrar",
				type: 'POST',
				dataType: 'json',
				beforeSubmit: function(arr, $form, options){
					if ($form.valid()){
						$("#btnSalvar").button('loading');
					}
					return $form.valid();
				},
				success: function(retorno){
					if (retorno.status == true){
						formCadastro.clear();
						message.success(retorno.conteudo);
						tabelaListagem.fnReloadAjax(controlador + "listarUsuarios");
						tabs.find('a[href="#listagem"]').tab('show');
					} else {
						message.error(retorno.conteudo);	
					}
					$("#btnSalvar").button('reset');
				}
			});
		}
	});
	
	var tabelaListagem = $('#tabela-listagem').dataTable({
		'aoColumns': [
		     {sTitle: 'ID', bVisible: false},
		     {sTitle: 'Nome'},
		     {sTitle: 'E-mail',  sWidth: '200px'},
		     {sTitle: 'Perfil',  sWidth: '150px'},
		     {sTitle: 'Status', sWidth: '100px'},
		     {sTitle: 'Ações', sClass: 'center',  sWidth: '40px', bSortable: false}
		],
        "aaSorting": [
            [0, 'desc']
        ],
        "sAjaxSource": controlador + "listarUsuarios"
    });
	
	$('a[data-acao="editar"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		}; 
		editar(params);
	});
	
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
	
	$('a[data-acao="visualizar"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		};
		visualizar(params);
	});
	
	$('a[data-acao="excluir"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		};
		excluir(params);
	});
	
	$('a[href="#cadastro"]').click(function() {
		formCadastro.enable();
		formCadastro.clear();
	});
	
	confirmaExclusao.click(function(){
		var params = JSON.parse($(this).attr('params'));
		$.post(params.url, {id: params.id}, function(retorno) {
			if (retorno.status == true) {
				tabelaListagem.fnReloadAjax(controlador + "listarUsuarios");
				message.success(retorno.conteudo);
			} else {
				message.error(retorno.conteudo);
			}
			
			Util.toTop();
			
		}, 'json');
	});
	
	$('#btnCancelar').click(function(){
		tabs.find('a[href="#listagem"]').tab('show');
		formCadastro.clear();
	});
	
});

/**
 * params: {id, url}
 */
var excluir = function(params) {
	confirmaExclusao.attr('params', JSON.stringify(params));
	modalExcluir.modal('show');
};

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

var preencherForm = function(registro) {
	formCadastro.clear();
	var camposForm = [
      	{id: 'id', type: FormType.TYPE_HIDDEN, value: registro.id},
      	{id: 'ativo', type: FormType.TYPE_CHECKBOX, value: registro.ativo},
      	{id: 'nome', type: FormType.TYPE_TEXT, value: registro.nome},
      	{id: 'perfil', type: FormType.TYPE_SELECT, value: registro.perfil.id},
      	{id: 'email', type: FormType.TYPE_TEXT, value: registro.email}
    ];
	formCadastro.preencher(camposForm);
};