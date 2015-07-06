var FormType = {
	TYPE_TEXT: 'text',
	TYPE_HIDDEN: 'hidden',
	TYPE_SELECT: 'select',
	TYPE_PASSWORD: 'password',
	TYPE_TEXTAREA: 'textarea',
	TYPE_CHECKBOX: 'checkbox',
	TYPE_RADIO: 'radio'
};
var Form = function(idform) {
	return {
		self: $(idform),
		clear: function() {
			this.self.find('input[type="text"], input[type="password"], input[type="hidden"], textarea').val('');
			this.self.find('select').select2('val', '');
			this.self.find('input[type="checkbox"], input[type="radio"]').attr('checked',false)
					 .parent().removeClass('checked');
		},
		disable: function() {
			this.self.find('input, button, select, textarea').attr('disabled', true);
		},
		enable: function() {
			this.self.find('input, button, select, textarea').attr('disabled', false);
		},
		preencher: function(campos) {
			/*
			campos = [{
				id: null,
				type: null,
				value: null
			}, ...];
			*/
			
			$.each(campos, function(i, campo){
				// TEXT, HIDDEN, SELECT
				if ($.inArray(campo.type, [FormType.TYPE_TEXT, FormType.TYPE_HIDDEN, FormType.TYPE_SELECT, FormType.TYPE_PASSWORD]) != -1) {
					if (campo.type == FormType.TYPE_SELECT) {
						$("#" + campo.id).select2('val', campo.value);
					} else {
						$("#" + campo.id).val(campo.value);
					}
				}
				
				// TEXTAREA
				if (campo.type == FormType.TYPE_TEXTAREA) {
					$("#" + campo.id).val(campo.value);
				}
				
				// CHECKBOX, RADIO
				if ($.inArray(campo.type, [FormType.TYPE_CHECKBOX, FormType.TYPE_RADIO]) != -1) {
					$("#" + campo.id).attr('checked', campo.value)
						.parent().addClass(campo.value ? 'checked' : '');
				}
			});
		}
	};
}