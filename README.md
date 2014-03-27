#Aplicativo de gerenciamento de Orçamentos

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
