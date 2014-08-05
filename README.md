#Aplicativo de gerenciamento de Orçamentos

### Configure o Apache VirtualHost

	<VirtualHost *:80>
        DocumentRoot "/vagrant/orcamentos"
        ServerName orcamentos.dev

        <Directory "/vagrant/orcamentos">
                Options Indexes Multiviews FollowSymLinks
                AllowOverride All
                Order allow,deny
                Allow from all


                RewriteEngine On
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule !\.(js|ico|gif|jpg|png|css|htm|html|txt|mp3)$ index.php
        </Directory>
	</VirtualHost>

É preciso criar o alias para o endereço _orcamentos.dev_ no seu /etc/hosts (Linux ou Mac)

        127.0.0.1 orcamentos.dev

### Instalação

        php composer.php self-update
        php composer.phar update

### Configuração

Basta duplicar o arquivo config/config.php.sample para config/config.php e mudar as configurações

### Criação da base de dados

O projeto usa o Doctrine, então é preciso criar a base de dados (de acordo com as configurações do config.php) e executar:
        
        ./vendor/bin/doctrine orm:schema-tool:create

### Exemplo de uso

[https://www.youtube.com/watch?v=r5OAGWhk2iQ](https://www.youtube.com/watch?v=r5OAGWhk2iQ)