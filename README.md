[![Codacy Badge](https://api.codacy.com/project/badge/6e6f386c74e04bb3bb48575ccce6b480)](https://www.codacy.com/app/eminetto/orcamentos)

#Aplicativo de gerenciamento de Orçamentos

### Configure o Apache VirtualHost

	<VirtualHost *:80>
        DocumentRoot "<sua pasta de projetos>/<nome escolhido ao clonar>" #ver instruções abaixo
        ServerName orcamentos.dev

        <Directory "<sua pasta de projetos>/<nome escolhido ao clonar>">
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

`127.0.0.1 orcamentos.dev`

Para criação de um virtual host no **Windows**, siga as instruções encontradas [neste link](http://www.emersoncarvalho.com/web/configurando-virtual-hosts-no-windows/).

### Instalação

Clonar o projeto:

Através da linha de comando, acessar sua pasta de projetos e clonar com `git clone git@github.com:Coderockr/orcamentos.git <nome da pasta do projeto>`

Acessar a pasta criada `cd <nome da pasta do projeto>` e atualizar o Composer fornecido:

`php composer.phar self-update`

Instalar as dependências do projeto:

`php composer.phar update`

O projeto também necessita da extensão intl do PHP para trabalhar com datas. 

### Configuração

Basta duplicar o arquivo `config/config.php.sample` para `config/config.php` e mudar as configurações de acesso ao banco de dados.

### Criação da base de dados

O projeto usa o Doctrine, então é preciso criar a base de dados (de acordo com as configurações do config.php) e executar:

`./bin/orcamentos orcamentos:initialize`

### Exemplo de uso

[https://www.youtube.com/watch?v=r5OAGWhk2iQ](https://www.youtube.com/watch?v=r5OAGWhk2iQ)
