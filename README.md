#Silex and Doctrine

#Install

### Install composer
	curl -s https://getcomposer.org/installer | php
### Execute

	php composer.phar create-project -s dev eminetto/silex-sample project_name

### Configure database connection
	Modify database connection parameters in bootstrap.php
	Create database
### Execute
	./bin/doctrine orm:schema-tool:create
	
### Configure Apache VirtualHost

	<VirtualHost *:80>
        DocumentRoot "/vagrant/silex-sample"
        ServerName silex-sample.dev

        <Directory "/vagrant/silex-sample">
                Options Indexes Multiviews FollowSymLinks
                AllowOverride All
                Order allow,deny
                Allow from all


                RewriteEngine On
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule !\.(js|ico|gif|jpg|png|css|htm|html|txt|mp3)$ index.php
        </Directory>
	</VirtualHost>
