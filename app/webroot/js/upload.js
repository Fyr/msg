var sum_bitrate = 0, count_bitrate = 0;

$(function () {
	$('#fileupload').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = 'Chat';
			$('#progress-bar').hide();
			$('#processFile').show();

			$('.inputFile').hide();
			$.post(mediaURL.move, file, function(response){
                $('#processFile').hide();
                $('.inputFile').show();
                if (checkJson(response)) {
                	var fileData = response.data[0].Media;
                	Chat.sendFile(fileData);
                }
            }, 'json');
		},
		add: function (e, data) {
			if (e.isDefaultPrevented()) {
				return false;
			}
			$('.inputFile').hide();
			$('#progress .progress-bar').css('width', 0);
			$('#progress-bar').show();
			$('#progress-stats').html('&nbsp;');
			// $('#processFile').show();
			
			data.submit();
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress .progress-bar').css('width', progress + '%');
			sum_bitrate+= data.bitrate;
			count_bitrate++;
			var avg_bitrate = sum_bitrate / count_bitrate;
			var html = Format.bitrate(avg_bitrate) + ' | ' +
				Format.time((data.total - data.loaded) * 8 / avg_bitrate) + ' | ' +
				Format.percentage(data.loaded / data.total) + ' | ' +
				Format.fileSize(data.loaded) + ' / ' + Format.fileSize(data.total);
			$('#progress-stats').html(chatLocale.Loading); // html
		}
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
});

