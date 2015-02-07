## LiveHub

LiveHub is a site focused on bringing live video from variety of services.

This project is sponsored by [cosmicradio.tv](http://cosmicradio.tv/)

## Setup

### Requirements

* php (> 5.5)
* [composer](https://getcomposer.org/)
* [node.js](http://nodejs.org/) / [io.js](https://iojs.org/)
* [gulp](http://gulpjs.com/)
* [bower](http://bower.io/)

### Installation

* Clone the site via git
* Configure the server so that public directory is served
* Get the dependencies
	* `composer install`
	* `npm install`
	* `bower install`
* Create a copy `.env.example` called `.env` and set values accordingly in the new file
	* Make sure to set `APP_ENV` to local if developing or `production` if in production.
	* Also check `config` folder for a bunch more settings
* Migrate the database `php artisan migrate`
* For developing:
	* While developing keep gulp watch task running: `gulp`
	* Also might be helpful to generate ide helpers file `php artisan ide-helper:generate`
* For production:
	* Generate assets: `gulp production`
	* Optimize: `php artisan optimize`
* Generate an user for yourself: `php artisan auth:create`

### Updating (in production)

* `git pull`
* Update dependencies with install commands from above
* Migrate the database `php artisan migrate`
* Update assets `gulp production`
* Optimize `php artisan optimize`