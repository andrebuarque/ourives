$.extend(true, $.fn.dataTable.defaults, {
	"oLanguage": {
		"sProcessing":   "Processando...",
	    "sLengthMenu":   "Mostrar _MENU_ registros",
	    "sZeroRecords":  "Não foram encontrados resultados",
	    "sInfo":         "Mostrando de _START_ até _END_ de _TOTAL_ registros",
	    "sInfoEmpty":    "Mostrando de 0 até 0 de 0 registros",
	    "sEmptyTable":   "Nenhum registro encontrado",
	    "sInfoFiltered": "(filtrado de _MAX_ registros no total)",
	    "sInfoPostFix":  "",
	    "sSearch":       "",
	    "sUrl":          "",
	    "oPaginate": {
	        "sFirst":    "Primeiro",
	        "sPrevious": "Anterior",
	        "sNext":     "Próxima",
	        "sLast":     "Última"
	    }
    },
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
    	var idRegistro = aData[0];
    	var botoes = aData[aData.length-1];
    	
	    	var divMenu = $("<div></div>").attr({
				"class": "btn-group pull-right"
			});
			var button = $("<button></button>").attr({
				"class": "btn btn-default dropdown-toggle",
				"data-toggle": "dropdown",
			});
			var iconeButton = $("<i></i>").attr({
				"class": "icon-align-justify"
			}).css({"margin-right": "3px"});
			var spanButton = $("<span></span>").attr({
				"class": "caret"
			});
			var ul = $("<ul></ul>").attr({
				"class": "dropdown-menu"
			});
			
			button.append(iconeButton).append(spanButton);
			
			$.each(botoes, function(i, obj){
				var li = $("<li></li>");
				var a = $("<a></a>").attr({
					"href": "#",
					'data-acao': obj.acao,
					'data-url': obj.url,
					'data-idregistro': idRegistro
				});
				var i = $("<i></i>").attr({
					"class": obj.classIcone
				});
				
				a.append(i).append(" "+ obj.titulo);	
				li.append(a);	
				ul.append(li);
			});
			
			divMenu.append(button).append(ul);
			$('td:last', nRow).html( divMenu );
    }
});

$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
{
    // DataTables 1.10 compatibility - if 1.10 then versionCheck exists.
    // 1.10s API has ajax reloading built in, so we use those abilities
    // directly.
    if ( $.fn.dataTable.versionCheck ) {
        var api = new $.fn.dataTable.Api( oSettings );
 
        if ( sNewSource ) {
            api.ajax.url( sNewSource ).load( fnCallback, !bStandingRedraw );
        }
        else {
            api.ajax.reload( fnCallback, !bStandingRedraw );
        }
        return;
    }
 
    if ( sNewSource !== undefined && sNewSource !== null ) {
        oSettings.sAjaxSource = sNewSource;
    }
 
    // Server-side processing should just call fnDraw
    if ( oSettings.oFeatures.bServerSide ) {
        this.fnDraw();
        return;
    }
 
    this.oApi._fnProcessingDisplay( oSettings, true );
    var that = this;
    var iStart = oSettings._iDisplayStart;
    var aData = [];
 
    this.oApi._fnServerParams( oSettings, aData );
 
    oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
        /* Clear the old information from the table */
        that.oApi._fnClearTable( oSettings );
 
        /* Got the data - add it to the table */
        var aData =  (oSettings.sAjaxDataProp !== "") ?
            that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;
 
        for ( var i=0 ; i<aData.length ; i++ )
        {
            that.oApi._fnAddData( oSettings, aData[i] );
        }
         
        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
 
        that.fnDraw();
 
        if ( bStandingRedraw === true )
        {
            oSettings._iDisplayStart = iStart;
            that.oApi._fnCalculateEnd( oSettings );
            that.fnDraw( false );
        }
 
        that.oApi._fnProcessingDisplay( oSettings, false );
 
        /* Callback user function - for event handlers etc */
        if ( typeof fnCallback == 'function' && fnCallback !== null )
        {
            fnCallback( oSettings );
        }
    }, oSettings );
};

$(function(){
	$('.dataTables_filter input').addClass("form-control input-sm").attr("placeholder", "Buscar");
	$('.dataTables_length select').select2();
});