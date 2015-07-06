var controlador = BASE + '/cadastroPerfil/';
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
		     {sTitle: 'Título'},
		     {sTitle: 'Status', sWidth: '100px'},
		     {sTitle: 'Ações', sClass: 'center',  sWidth: '40px', bSortable: false}
		],
        "aaSorting": [
            [0, 'desc']
        ],
        "sAjaxSource": controlador + "listarPerfis"
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
				tabela_listagem.fnReloadAjax(controlador + "listarPerfis");
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
	});
	
	$("input[type='checkbox']").on("ifToggled", function(event){
		var idmenupai = $(this).attr('data-idmenupai');
		var isChecked = $(this).attr('checked') == 'checked';
		var checkboxes = ['visualizar', 'gravar', 'excluir'];
		
		for (i = 0; i < checkboxes.length; i++) {
			var checkbox = checkboxes[i];
			var id = $(this).attr('id');
			if ($(this).hasClass(checkbox)) {
				var menusFilhos = formCadastro.self.find('[name^="'+checkbox+'"][data-idmenupai="'+id+'"]');
				if (menusFilhos.length >= 1) {
					if (isChecked) {
						menusFilhos.attr('checked', true).parent().addClass('checked');
					} else {
						menusFilhos.attr('checked', false).parent().removeClass('checked');
					}
					break;
				}
			}
		}
		
		if ($(this).hasClass('gravar')) {
			var checkVisualizar = $(this).attr('name').replace('gravar', 'visualizar');
			if (isChecked) {
				formCadastro.self.find('input[name="'+ checkVisualizar +'"]').attr('checked', true).parent().addClass('checked');
			}
		}
	});
	
	formCadastro.self.validate({
		rules: {
		    titulo: "required"
		},
		messages: {
			titulo: {
				required: "Campo obrigatório."
			}
		},
		submitHandler: function(form) {
			$.post(controlador + "inserirPerfil", $(form).serialize(), function(retorno) {
				if (retorno.status == true) {
					tabela_listagem.fnReloadAjax(controlador + "listarPerfis");
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
    	{id: 'ativo', type: FormType.TYPE_CHECKBOX, value: registro.ativo},
    	{id: 'titulo', type: FormType.TYPE_TEXT, value: registro.titulo}
    ];
	
	formCadastro.preencher(camposForm);
	
	$.each(registro.permissoes, function(i, permissao) {
		var idmenu = permissao.menu.id;
		
		if (permissao.visualizar) {
			formCadastro.self.find('input[name="visualizar[' + idmenu + ']"]').attr('checked', true).parent().addClass('checked');
		}
		
		if (permissao.gravar) {
			formCadastro.self.find('input[name="gravar[' + idmenu + ']"]').attr('checked', true).parent().addClass('checked');
		}
		
		if (permissao.remover) {
			formCadastro.self.find('input[name="excluir[' + idmenu + ']"]').attr('checked', true).parent().addClass('checked');
		}
	});
};