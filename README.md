#Aplicativo de gerenciamento de Orçamentos

### Configure o Apache VirtualHost

```apache
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
```

É preciso criar o alias para o endereço _orcamentos.dev_ no seu /etc/hosts (Linux ou Mac)

```ini
127.0.0.1   orcamentos.dev
```

Para criação de um virtual host no **Windows**, siga as instruções encontradas [neste link](http://www.emersoncarvalho.com/web/configurando-virtual-hosts-no-windows/).

### Instalação

Clonar o projeto:

Através da linha de comando, acessar sua pasta de projetos e clonar com `git clone git@github.com:Coderockr/orcamentos.git <nome da pasta do projeto>`

Acessar a pasta criada `cd <nome da pasta do projeto>` e atualizar o Composer fornecido:

```bash
$ php composer.phar self-update
```

Instalar as dependências do projeto:

```bash
$ php composer.phar update
```

### Linhas de comando

Para facilicar algumas tarefas do projeto foi criado uma interface de linha de comando que pode ser executada de duas formas:

```bash
$ ./bin/orcamentos
```

ou

```bash
$ php bin/orcamentos
```

Executando o comando acima será exibido os comandos disponiveis, para executar algum comando disponível basta exectuar:

```bash
$ ./bin/orcamentos comando
```

ou

```bash
$ php bin/orcamentos comando
```

### Configuração

Basta duplicar o arquivo `config/config.php.sample` para `config/config.php` e mudar as configurações de acesso ao banco de dados.

### Criação da base de dados

O projeto usa o Doctrine, então é preciso criar a base de dados (de acordo com as configurações do config.php) e executar:

```bash
$ ./bin/orcamentos orcamentos:initialize
```

**OBS:** O comando acima deve ser executado apenas para criação do projeto.

### Atualizando banco de dados

Para facilitar a evolução do banco foi utilizado [Doctrine Migrations](http://www.doctrine-project.org/projects/migrations.html) uma ferramenta de versionamento para banco de dados.

Para efetuar qualquer alteração no banco de dados, basta criar e/ou alterar e/ou remover qualquer atributos e/ou entidade presente na pasta `src/Orcamentos/Model` e executar:

```bash
$ ./bin/orcamentos migrations:diff
```

Esse comanda irá comprar os mapeamentos com banco configura e gerar um novo arquivo em `src/Orcamentos/Migrations` com essas alterações.

Para aplicar as diferenças ao banco basta executar:

```bash
$ ./bin/orcamentos migrations:migrate
```

Para outros comandos ou dúvida basta acessar a documentação do [Doctrine Migrations](http://www.doctrine-project.org/projects/migrations.html).

### Exemplo de uso

[https://www.youtube.com/watch?v=r5OAGWhk2iQ](https://www.youtube.com/watch?v=r5OAGWhk2iQ)
