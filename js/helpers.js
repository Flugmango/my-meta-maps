var FormErrorMessages = {

	errorClass: 'invalid',
	
	apply: function(form, json) {
		this.remove(form);
		var that = this;
		$.each(json, function(field, message) {
			var elm = $(form).find("*[name='" + field + "']").parent(".form-group");
			elm.addClass(that.errorClass);
			elm.find('.error-message').text(message);
		});
	},
	
	remove: function(form) {
		$(form).find("." + this.errorClass).removeClass(this.errorClass);
	}
	
};

var MessageBox = {

	dismissPermanently: function(name) {
		console.log("Dismissing message: " + name);
		// Remove message box
		$('#' + name).remove();
		// Cookie with the specified name contains a 1 to signal it should be hidden permanently
		document.cookie = escape(name) + "=1; expires=Mon, 30 Dec 2030 00:00:00 GMT; path=/";
	},
	
	addError: function(message, title) {
		this.add(message, 'danger', title);
	},
	
	addSuccess: function(message, title) {
		this.add(message, 'success', title);
	},
	
	addWarning: function(message, title) {
		this.add(message, 'warning', title);
	},
	
	addInfo: function(message, title) {
		this.add(message, 'info', title);
	},
	
	add: function (message, className, title) {
		var html = '<div class="alert alert-' + className + ' alert-dismissible">';
		html += '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Schließen</span></button>';
		if (title) {
			html += '<strong>' + title + '</strong>&nbsp;&nbsp;';
		}
		html += message + '</div>';
		var element = $().add(html);
		$('#messages').append(element);
		element.delay(10000).fadeOut(2000);
	}
	
};