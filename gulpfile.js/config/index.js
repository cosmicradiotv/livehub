var env = require('node-env-file');
env('./.env');

var config = {};

config.publicDirectory = "./public";
config.sourceDirectory = "./resources";
config.publicAssets = config.publicDirectory + "/assets";
config.sourceAssets = config.sourceDirectory + "/assets";

config.watch = {
	usePolling: !!process.env.GULP_VM,
	interval: 500
};

module.exports = config;

