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

### Configuração

Basta duplicar o arquivo `config/config.php.sample` para `config/config.php` e mudar as configurações de acesso ao banco de dados.

### Criação da base de dados

O projeto usa o Doctrine, então é preciso criar a base de dados (de acordo com as configurações do config.php) e executar:

`./vendor/bin/doctrine orm:schema-tool:create`

**Importante**: A extensão APC é um pré-requisito para o projeto. Caso encontre erros ao rodar o comando acima, instale a extensão.

**Importante**: Nas versões mais atuais do PHP (5.5+) a extensão APC não é mais compatível. Uma solução é instalar o php5-apcu.

### Acesso ao sistema

Antes de acessar o sistema é necessário criar o primeiro usuário, rodando **através da linha de comando** o seguinte script:

`cd <pasta do projeto>/docs/` e `php firstUser.php "Nome da Empresa" "Nome da pessoa responsável" "(xx) xxxx-xxxx" "e-mail da pessoa responsável" "usuario" "senha" "e-mail do usuário"`.

**Dica**: substituir as informações entre as aspas por suas próprias informações.

### Exemplo de uso

[https://www.youtube.com/watch?v=r5OAGWhk2iQ](https://www.youtube.com/watch?v=r5OAGWhk2iQ)