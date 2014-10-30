Format = {
	tag: function(tagName, attrs, innerHtml) {
		var html = '<' + tagName;
		for(var i in attrs) {
			html+= ' ' + i + '="' + attrs[i] + '"';
		}
		if (innerHtml) {
			html+= '>' + innerHtml + '</' + tagName + '>';
		} else {
			html+= '/>';
		}
		return html;
	},
	img: function(attrs) {
		if (typeof(attrs) == 'string') {
			attrs = {src: attrs, alt: ''}
		}
		return Format.tag('img', attrs);
	},
	fileSize: function(value) {
		if (typeof(value) == 'number') {
			var sizes = ['', 'Kb', 'Mb', 'Gb'];
			for (var i = 0; i < sizes.length; i++) {
				if (value < 1024 || i == (sizes.length - 1)) {
					return value.toFixed(2) + sizes[i];
				}
				value = value / 1024;
			}	
		}
		return '';
	},
	bitrate: function(bits) {
		return this.fileSize(bits) + 'its/sec';
	},
	time: function (seconds) {
		var date = new Date(seconds * 1000),
		days = Math.floor(seconds / 86400);
		days = days ? days + 'd ' : '';
		return days +
			('0' + date.getUTCHours()).slice(-2) + ':' +
			('0' + date.getUTCMinutes()).slice(-2) + ':' +
			('0' + date.getUTCSeconds()).slice(-2);
	},
	percentage: function (floatValue) {
		return (floatValue * 100).toFixed(2) + '%';
	}
}