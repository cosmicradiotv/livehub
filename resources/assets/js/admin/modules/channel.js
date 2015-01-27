var $ = require('jquery');

var Channel = module.exports = function (app) {
	this.app = app;
};

Channel.prototype.call = function (method) {
	this.methods[method].apply(this);
};

Channel.prototype.methods = {
	// this = module instance
	'add': function () {
		var module = this;

		$('[data-service]').on('change', function () {
			module.loadServiceSettings($(this).val(), $('#channel-service-settings'));
		});
	},
	'edit': function () {
		var module = this;

		$('[data-service]').on('change', function () {
			module.loadServiceSettings($(this).val(), $('#channel-service-settings'));
		});
	}
};

Channel.prototype.loadServiceSettings = function (service_id, $target) {
	var module = this;

	$target.html('<div class="alert-box info">Loading settings...</div>');

	var url = module.app.config['service-settings-url'].replace(encodeURIComponent('{incoming_service}'), service_id);

	$.ajax(url).done(function (content) {
		$target.html(content);
	}).fail(function () {
		$target.html('Server error');
	});
};