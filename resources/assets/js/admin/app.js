var $ = require('jquery');

var adminApp = module.exports = function (config) {
	var app = this;

	app.config = config;
	app.modules = {
		'channel': require('./modules/channel')
	};
	app.page = null;

	if(config.module) {
		app.init(config.module);
	}
};

adminApp.prototype.init = function (module) {
	var app = this;

	app.page = new app.modules[module[0]](app);

	app.page.call(module[1]);
};