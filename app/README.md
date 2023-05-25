This directory contains all the files for the web app.

How to make them work:

Make sure mariadb and apache are running (and listening in port 80), use the sql scripts to initialize the database as described in the "sql" directory. Then place all files from this directory into the Apache directory (usually it is /var/www or htdocs. If you want to use a different directory, you may need to configure Apache's document root) and enter the localhost from your browser.

How it works:

The .htaccess file redirects all requests to index.php, which is the file that handles the routes.




notes:
make sure php module exists:
	sudo apt-get install php
		confirm install: php -v
	sudo a2enmod php8.1
		(where 8.1 may be different for you, ckeck php -v
		  also if it is 8.1.2 only add 8.1)
		(confirm module enabled)
	sudo service apache2 restart
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

/* nessecary you need to run this every time you change the files */
sudo chmod -R 777 /var/www


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










			
