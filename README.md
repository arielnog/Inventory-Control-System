# Inventory Control System [![Tweet](https://img.shields.io/twitter/url/http/shields.io.svg?style=social&logo=twitter)](https://twitter.com/intent/tweet?text=CoreUI%20-%20Free%20Bootstrap%204%20Admin%20Template%20&url=https://coreui.io&hashtags=bootstrap,admin,template,dashboard,panel,free,angular,react,vue)

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

> O projeto consiste em um sistema de controle de estoque, o usuário tem acesso a um dashboard para avaliar seus lucros e gastos (mensais e anuais), além disso, o usuário tem acesso a dois modulos de gestão (Produtos(Products) e Relatórios(Reports)) onde é possivel organizar compras e vendas de produtos com facilidade. Utilizo Laravel no back-end para fornecer a API para o front-end em Vue.js, as requisições são feitas através do axios e o layout totalmente responsivo.
<hr>
<img src="amostragem5.gif" />

## Instalação

``` bash

# instale as dependências na pasta "laravel"
$ composer install

# instale as dependências na pasta "laravel"
$ npm install
```

Copie o arquivo ".env.example", e mude o nome do mesmo para ".env".
Dentro do arquivo ".env" complete a seguinte configuração de acordo com seu banco de dados:

* DB_CONNECTION=mysql
* DB_HOST=127.0.0.1
* DB_PORT=3306
* DB_DATABASE=laravel
* DB_USERNAME=root
* DB_PASSWORD=

### Proximo passo

``` bash
# Ainda na pasta laravel, execute (Obs: talvez seja necessário liberar algumas extensões no seu php.ini):
$ php artisan key:generate

$ php artisan jwt:secret

$ php artisan migrate:refresh --seed

```

```bash
# Entre na pasta "coreui"
$ cd ../coreui

# Instale as dependências
$ npm install

# Abra a pasta node_modules/axios/lib, abra o arquivo axios.js e adicione está linha antes do (module.exports = axios)
$ axios.defaults.baseURL = "http://127.0.0.1:8000";

# Execute o seguinte comando para subir o servidor do front-end:
$ npm run serve

```

``` bash
# Na pasta "laravel" (em outro prompt de comando), execute o comando para subir o servidor do back-end:
$ php artisan serve

```

Acesse a aplicação através de: [localhost:8080](localhost:8080)
Obs: O acesso abaixo é apenas para o usuário admin. Crie um usuário para acessar como user normal e utilizar as funcionalidades. 

* E-mail: _admin@admin.com_
* Password: _password_

## Criador do Sistema

**Vinicius Nery**

Template: CoreUI

* <https://www.linkedin.com/in/marcos-nery-a3012/>
* <https://github.com/Nery37>



