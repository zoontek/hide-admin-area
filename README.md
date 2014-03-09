### What it does

Admin Redirect is a small and dumb (…and crappy) WordPress plugin which changes the admin area url, and avoid access to wp-login.php or wp-admin using a secret key.
Only use this if, for some obscure reason, you can't edit the .htaccess file / the nginx rules.

### How-to use it

1. Extract the `admin-redirect-master` directory to your computer. Rename it if you want. Or use git clone, it's faster.
2. Change the `ADMIN_PAGE` value in the `admin-redirect.php` file to what-the-fuck-you-want. Admin area will be available at `mywebsite.com/what-the-fuck-you-want`
3. Change the `SECRET_KEY` value using a password generator, for example
4. Upload the `admin-redirect` directory to your WordPress plugins directory (Default is `/wp-content/plugins/`)

### Changelog

1. Version 1.0, March 2014
..* Initial release.
