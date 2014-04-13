### What it does

Hide Admin Area is a (dumb) WordPress plugin which changes the admin area URL, and avoid access to wp-login.php or wp-admin using a secret key.

### How-to use it

1. You know how to install WordPress plugins, right?
2. Don't forget to activate it.
3. By default, admin area is available at `http://my-website.com/where-my-wordpress-is/hidden-admin`
4. You can change the admin area URL slug in WP general settings.

### Why is this dumb?

* You can't create something that will share the same URL as your admin area. So avoid using the same slug.
* Come on. You have httpd.conf, .htaccess files, nginx rules. Don't be that guy who use WP plugins for everything.
* Only use this if, for some obscure reason, you really can't do what I just say.

### Changelog

* Version 1.1.1, April 2014
  * Change target hook.

* Version 1.1, March 2014
  * Code cleaning. It's not because it's dumb that it has to be crappy.
  * You don't have to think about the secret key now. It's automatically generated at plugin activation.
  * You can now modify the admin URL slug in WP general settings.

* Version 1.0, March 2014
  * Initial release.
