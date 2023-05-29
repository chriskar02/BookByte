This directory contains all the files for the web app.

How to make them work:

Make sure mariadb and apache are running (and listening in port 80), use the sql scripts to initialize the database as described in the "sql" directory. Then place all files from this directory into the Apache directory (usually it is /var/www or htdocs. If you want to use a different directory, you may need to configure Apache's document root) and enter the localhost from your browser.

How it works:

The .htaccess file redirects all requests to index.php, which is the file that handles the routes.




notes:
make sure php module exists:
	`sudo apt-get install php`
		confirm install: `php -v`
	`sudo a2enmod php8.1`
		(where 8.1 may be different for you, ckeck php -v
		  also if it is 8.1.2 only add 8.1)
		(confirm module enabled)
	`sudo service apache2 restart`
	open php.ini and make sure that short_open_tag = On:
		cat /etc/php/php.ini or
			/etc/phpX/php.ini (where "X" represents the PHP version number), or
			/etc/php/X/php.ini (where "X" represents the PHP version number), or
			/etc/php/X/cli/php.ini (where "X" represents the PHP version number)

		(use nano sudo [filename] to change it if it is not On)
	enter apache config file somewhere at:
		/etc/apache2/apache2.conf
		/etc/httpd/httpd.conf
		and loook for:
			AddType application/x-httpd-php .php
			AddHandler application/x-httpd-php .php
			if they dont exist, add them
			restart
				sudo service apache2 restart

/* nessecary you need to run this every time you reupload (not change, but delete and paste again) the files */
`sudo chmod -R 777 /var/www`


in /etc/apache2/apache2.conf (use sudo):
	<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All 
        Require all granted
	</Directory>

check is mod_reqwrite is enabled:
	apache2ctl -M
	if not:
		sudo a2enmod rewrite
		sudo systemctl restart apache2

exeute in mysql console:
	FLUSH PRIVILEGES;
	ALTER USER 'root'@'localhost' IDENTIFIED BY 'new_password';

    Locate the PHP configuration file called php.ini. The location of this file may vary depending on your operating system and PHP installation.
    Open php.ini in a text editor.
    Search for the error_reporting directive. Uncomment it if it is commented out by removing the semicolon at the beginning of the line.
    Set the value of error_reporting to E_ALL. This setting enables reporting of all types of errors, warnings, and notices.
    Search for the display_errors directive. Uncomment it if necessary.
    Set the value of display_errors to On. This setting allows PHP errors to be displayed on the screen.
    Save the changes to php.ini and restart your web server for the changes to take effect.
    
`sudo nano /etc/php/8.1/cli/php.ini`








			
