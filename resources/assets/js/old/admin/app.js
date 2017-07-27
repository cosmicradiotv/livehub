import $ from 'jquery';
import channelModule from './modules/channel';
import showChannelModule from './modules/show-channel';


var adminApp = function (config) {
	var app = this;

	app.config = config;
	app.modules = {
		'channel': channelModule,
		'show-channel': showChannelModule
	};
	app.page = null;

	if(config.module) {
		app.init(config.module);
	}
};

adminApp.prototype.init = function (module) {
	var app = this;

	if(app.modules[module[0]]) {
		app.page = new app.modules[module[0]](app);

		app.page.call(module[1]);
	}
};

export default adminApp