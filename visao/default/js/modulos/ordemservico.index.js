var controlador = BASE + '/cadastroOrdemServico/';
var tabs = $('#tabs');
var formCadastro = Form('#form-cadastro');
var message = Message('#message');
var modalExcluir = $('#modalExcluir');
var modalCamera = $('#modalCamera');
var modalVisualizarFotos = $('#modalVisualizarFotos');
var confirmaExclusao = $('#confirmaExclusao');
var modalProcurarClientes = $("#modalProcurarCliente");
var video = document.querySelector("#video");
var totalFotos = 0;
var linkTotalFotos = 0;
var modalValidarPagamento = $('#modalValidarPagamento');

$(function(){

	modalExcluir.modal('hide');
	modalCamera.modal('hide');
	
	// formatando inputs
	$('#dataprevistaentrega').mask('99/99/9999');
	$('#dataprevistaentrega').datepicker();
	$('select').select2({
		width: '100%'
	});
	
	$('#valor, #valorpago, #valorFinalizacaoOS').maskMoney({
		symbol:'R$', // Simbolo
		decimal:'.', // Separador do decimal
		precision:2, // Precisão
		thousands:'', // Separador para os milhares
		allowZero: true
	});
	
	
	var tabela_listagem = $('#tabela-listagem').dataTable({
		'aoColumns': [
		     {sTitle: 'ID', bVisible: false},
		     {sTitle: 'O.S.', sWidth: '70px'},
		     {sTitle: 'Tipo Serviço', sWidth: '170px'},
		     {sTitle: 'Cliente', sWidth: '200px'},
		     {sTitle: 'Data Solicitação', sWidth: '80px'},
		     {sTitle: 'Data Prevista', sWidth: '80px'},
		     {sTitle: 'Ourives', sWidth: '200px'},
		     {sTitle: 'Valor', sWidth: '100px'},
		     {sTitle: 'Status', sWidth: '100px'},
		     {sTitle: 'Ações', sClass: 'center',  sWidth: '80px', bSortable: false}
		],
        "aaSorting": [
            [0, 'desc']
        ],
        "sAjaxSource": controlador + "listar"
    });
	
	$("input[name='tipoOS']").on("click", function(event){
		var opcao = $(this).attr('id');
		
		if (opcao == 'tipoOSProduto') {
			$('#divTitulo').hide();
			$('#divProduto').show();
		} else {
			$('#divTitulo').show();
			$('#divProduto').hide();	
		}
	});
	
	$('a[data-acao="excluir"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		};
		excluir(params);
	});
	
	// listagem de clientes
	var tabelaListagemClientes = $('#tabela-listagem-clientes').dataTable({
		'aoColumns': [
		     {sTitle: 'ID', bVisible: false},
		     {sTitle: 'Nome'},
		     {sTitle: 'CPF', sWidth: '200px'},
		     {sTitle: 'E-mail', sWidth: '200px'}
		],
        "sAjaxSource": controlador + "listarClientes",
		"bLengthChange": false,
		"bInfo": false,
		"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$(nRow).attr({'data-id': aData[0]}).addClass('modalCliente').css("cursor", "pointer");
		}
    });
	
	$(".modalCliente").live("click", function(){
		var id = $(this).attr("data-id");
		$("#cliente").select2("val", id);
		modalProcurarClientes.modal("hide");
	});
	
	$("#btProcurarCliente").on("click", function(event){
		modalProcurarClientes.modal("show");
		tabelaListagemClientes.fnReloadAjax(controlador + "listarClientes");
	});
	
	formCadastro.self.validate({
		submitHandler: function(form){
			$(form).ajaxSubmit({
				url: controlador + "cadastrar",
				dataType: 'json',
				type: 'POST',
				beforeSubmit: function(arr, $form, options){
					if ($form.valid()){
						$("#btnSalvar").button('loading');
					}
					return $form.valid();
				},
				success: function(data, statusText, xhr, form){
					
					tabela_listagem.fnReloadAjax(controlador + "listar");
					tabs.find('a[href="#listagem"]').tab('show');
					message.success(data.conteudo);
					formCadastro.clear();
					$("#btnSalvar").button('reset');
					Util.toTop();
				},
				error: function(data, statusText, xhr, form){
					data = $.parseJSON(data.responseText);
					message.error(data.conteudo);
					$("#btnSalvar").button('reset');
					Util.toTop();
				}
			});
		}
	});
	
	$('a[data-acao="visualizar"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		};
		$("#divimagem").show();
		

		$("#fotosVisualizar").empty();
		$.post(controlador + "buscarImagensOS",
			{id: params.id},
			function(data){
				$.each(data.imagens, function(i, obj){
					novaDiv = $("#modeloImagem").clone();
					$(novaDiv).find("img").attr("src", data.path + obj.titulo);
					novaDiv.show();
					$(novaDiv).attr("data-id", obj.id);
					$(novaDiv).hover(
							function(){
								$(this).find("span").css("display", "block");
							}, 
							function(){
								$(this).find("span").css("display", "none");
							}
						);
					
					
					$("#fotosVisualizar").append(novaDiv);
				});
			},
		'json');
		
		
		visualizar(params);
	});
	
	$(".remover").live("click", function(event){
		if (confirm("Deseja excluír essa imagem?")){
			var div = $(this).parent(); 
			var id = $(div).attr("data-id");
			$.post(controlador + "excluirImagemOS",{id:id},
				function(data){
					if (data.status == true){
						$(div).remove();
					}
					alert(data.conteudo);
				},
			'json');			
		}
		event.stoppropagation();
	});

	$(".modalVisualizar").live("click", function(){
		var img = $(this).find("img").attr("src");
		$("#modalVisualizarImagem").find("img").attr("src", img);
		$("#modalVisualizarImagem").find("a").attr("href", img);
		$("#modalVisualizarImagem").modal("show");
	});
	
	$('a[data-acao="editar"]').live('click', function(){
		var params = {
			id: $(this).attr('data-idregistro'),
			url: controlador + $(this).attr('data-url')
		}; 
		$("#divimagem").hide();
		editar(params);
	});
	
	$('a[data-acao="tirarFotos"]').live('click', function(){
		modalCamera.modal('show');
		totalFotos = 0;
		$('#totalFotos').html(totalFotos);
		$('#fotos').html('');
		$("#idOSFoto").val($(this).attr('data-idregistro'));
		
		Camera.ligar(video);
	});
	
	$('#btnFoto,#video').on('click', function(){
		var foto = Camera.capturarImagem();
		var id = $("#idOSFoto").val();
		
		foto.style.margin = '3px 0 0 3px';
		foto.width *= 0.3;
		foto.height *= 0.3;
		$("#btnFoto").button('loading');
		if (foto.src != 'data:,') {
			$.post(controlador + "salvarImagem",
				{idos: id, url: foto.src},
				function(data){
					$("#btnFoto").button('reset');
					if (data.status == true){
						totalFotos++;
						$('#fotos').append(foto);
						$('#totalFotos').html(totalFotos);
					}
				},
			'json');
		}
	});
	
	modalCamera.on('hidden.bs.modal', function (e) {
		Camera.desligar();
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
	
	
	$( "#produto" ).rules("remove");
	$( "#titulo" ).rules( "add", {
		  required: true
	});

	$("input[name='tipoOS']").on("click", function(event){
		if ($("#tipoOSServico").is(":checked")){
			$( "#produto" ).rules("remove");
			$( "#titulo" ).rules( "add", {
				  required: true
			});
		} else if ($("#tipoOSProduto").is(":checked")){
			$( "#titulo" ).rules("remove");
			$( "#produto" ).rules( "add", {
				  required: true
			});
		}
	});
	
	$('#btnCancelar').click(function(){
		tabs.find('a[href="#listagem"]').tab('show');
		formCadastro.clear();
	});
	
	$('a[data-acao="concluirServico"]').live('click', function(){
		id = $(this).attr('data-idregistro');
		$.post(controlador + "concluirServico", {idos: id},
				function(data){
					
					if (data.status == true){
						message.success(data.mensagem);
					} else {
						message.error(data.conteudo);
					}
					tabela_listagem.fnReloadAjax(controlador + "listar");
			    }, 
		'json');
	});
	
	$('a[data-acao="cancelarOS"]').live('click', function(){
		id = $(this).attr('data-idregistro');
		$.post(controlador + "cancelarServico", {idos: id},
				function(data){
					
					if (data.status == true){
						message.success(data.mensagem);
					} else {
						message.error(data.conteudo);
					}
					tabela_listagem.fnReloadAjax(controlador + "listar");
			    }, 
		'json');
	});
	
	modalValidarPagamento.modal("hide");
	$('a[data-acao="finalizarOS"]').live('click', function(){
		id = $(this).attr('data-idregistro');
		$.post(controlador + "finalizarOS", {idos: id},
			function(data){
			
				if (data.status = true){
					
					if (data.erro == "pendente"){
						modalValidarPagamento.modal("show");
						modalValidarPagamento.attr('data-id-os', id);
					} else {
						message.success(data.mensagem);
						tabela_listagem.fnReloadAjax(controlador + "listar");
					}
					
				} else {
					message.error(data.conteudo);
				}
		
			},'json');
	});
	
	$('#btnFinalizarOS').live('click',function(){
		valorPago = $('#valorFinalizacaoOS').val().trim();
		idos = modalValidarPagamento.attr('data-id-os');
		$.post(controlador + "alterarValorPago", {idos: id, valorPago: valorPago},
			function(data){
				if (data.status = true){
					modalValidarPagamento.modal("hide");
					
					if (data.erro == ""){
						message.success(data.mensagem);
						tabela_listagem.fnReloadAjax(controlador + "listar");
					} else {
						message.error(data.erro);
					}
					
				} else {
					message.error(data.conteudo);
				}
		},'json');
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
	var idOurives = registro.ourives != null ? registro.ourives.id : 0;
	
	var camposForm = [
    	{id: 'id', type: FormType.TYPE_HIDDEN, value: registro.id},
    	{id: 'categoria', type: FormType.TYPE_SELECT, value: registro.categoria.id},
    	{id: 'ourives', type: FormType.TYPE_SELECT, value: idOurives},
    	{id: 'descricao', type: FormType.TYPE_TEXTAREA, value: registro.descricao},
    	{id: 'cliente', type: FormType.TYPE_SELECT, value: registro.cliente.id},
    	{id: 'valor', type: FormType.TYPE_TEXT, value: registro.valor},
    	{id: 'valorpago', type: FormType.TYPE_TEXT, value: registro.valorPago},
    	{id: 'dataprevistaentrega', type: FormType.TYPE_TEXT, value: registro.dataPrevistaEntrega},
    	{id: 'entregarpara', type: FormType.TYPE_TEXT, value: registro.entregarPara},
    	{id: 'observacao', type: FormType.TYPE_TEXTAREA, value: registro.observacao}
    ];
	
	if (registro.produto == null) {
		$('#divTitulo').show();
		$('#divProduto').hide();
		$('#titulo').val(registro.servico);
		$('#tipoOSServico').attr('checked', true);
	} else {
		$('#divTitulo').hide();
		$('#divProduto').show();
		$('#produto').select2('val', registro.produto.id);
		$('#tipoOSProduto').attr('checked', true);
	}
	
	formCadastro.preencher(camposForm);
};