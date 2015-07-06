var Camera = {
	video: null,
	stream: null,
	ligar: function(video) {
		this.video = video;
		
		navigator.getUserMedia = navigator.getUserMedia 
				|| navigator.webkitGetUserMedia 
				|| navigator.mozGetUserMedia 
				|| navigator.msGetUserMedia || 
				navigator.oGetUserMedia;
		
		if (navigator.getUserMedia) {
			navigator.getUserMedia({video: true},capturaVideo, errorVideo);
		}
		
		this.video.src = '';
		function capturaVideo(stream) {
			video.src = window.URL.createObjectURL(stream);
			Camera.stream = stream;
		}
		
		function errorVideo(e) {
			alert('Ocorreu um erro ao iniciar a câmera');
		}
	},
	capturarImagem: function() {
		var canvas = document.querySelector('canvas');
		canvas.width = this.video.videoWidth;
        canvas.height = this.video.videoHeight;
		var ctx = canvas.getContext('2d');
		
		ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
		var img = document.createElement('img');
		img.src = canvas.toDataURL('image/jpeg');
		img.width = canvas.width;
		img.height = canvas.height;
		return img;
	},
	desligar: function() {
		this.stream.stop();
		this.video.src='';
	}
};