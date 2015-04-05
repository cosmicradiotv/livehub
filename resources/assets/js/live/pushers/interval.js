import $ from 'jquery';

var Pusher = module.exports = function (config) {
	this.type = 'interval';
	this.active = false;
	this.config = config;
	this.ondata = null;
	this.onerror = null;

	this.timeout = null;
};

Pusher.prototype.start = function () {
	this.active = true;
	this.timeout = setTimeout(this.check.bind(this), this.config.frequency * 1000);
};

Pusher.prototype.check = function () {
	var that = this;

	if (!this.active) {
		return;
	}

	$.ajax(this.config.target).done(function (response) {
		var streams = response.data;

		if (that.ondata) {
			that.ondata(streams);
		}

		setTimeout(that.check.bind(that), that.config.frequency * 1000);
	});

};

Pusher.prototype.cancel = function () {
	clearTimeout(this.timeout);
	this.active = false;
};