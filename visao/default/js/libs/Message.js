var Message = function(iddiv) {
	return {
		classBotao: 'alert',
		timeOut: 5000,
		retornoTimeout: null,
		self: $(iddiv).append($('<button></button>').attr({'data-dismiss': 'alert', 'class': 'close'}).html('×'))
						.append($('<i> </i>'))
						.append($('<strong></strong>').attr('class', 'alert-message')).hide(),
		success: function(message) {
			clearTimeout(this.retornoTimeout);
			
			this.self.attr('class', this.classBotao + " alert-success");
			this.self.find('i').attr('class', 'icon-ok-sign');
			this.self.find('strong.alert-message').html(message);
			this.self.show();
			
			this.retornoTimeout = setTimeout(function(){
				$(iddiv).hide();
			}, this.timeOut);
		},
		error: function(message) {
			clearTimeout(this.retornoTimeout);
			
			this.self.attr('class', this.classBotao + " alert-danger");
			this.self.find('i').attr('class', 'icon-exclamation-sign');
			this.self.find('strong.alert-message').html(message);
			this.self.show();
			
			this.retornoTimeout = setTimeout(function(){
				$(iddiv).hide();
			}, this.timeOut);
		},
		warning: function(message) {
			clearTimeout(this.retornoTimeout);
			
			this.self.attr('class', this.classBotao + " alert-warning");
			this.self.find('i').attr('class', 'icon-exclamation-sign');
			this.self.find('strong.alert-message').html(message);
			this.self.show();
			
			this.retornoTimeout = setTimeout(function(){
				$(iddiv).hide();
			}, this.timeOut);
		}
	};
}