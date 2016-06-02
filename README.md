## Articles

[![Total Downloads](https://poser.pugx.org/mixdinternet/articles/d/total.svg)](https://packagist.org/packages/mixdinternet/articles)
[![Latest Stable Version](https://poser.pugx.org/mixdinternet/articles/v/stable.svg)](https://packagist.org/packages/mixdinternet/articles)
[![License](https://poser.pugx.org/mixdinternet/articles/license.svg)](https://packagist.org/packages/mixdinternet/articles)

![Área administrativa](http://mixd.com.br/github/ce4422be811a606f574a9e0a0bbb7130.png "Área administrativa")

Pacote inicial de cadastro de artigos.

## Dependencias
Os pacotes mixdinternet/seo e mixdinternet/galleries são dependencias deste pacote, siga as instruções na página dos respectivos pacotes:
* [Seo](https://github.com/mixdinternet/seo)
* [Galleries](https://github.com/mixdinternet/galleries)

## Instalação

Adicione no seu composer.json

```js
  "require": {
    "mixdinternet/articles": "0.1.*"
  }
```

ou

```js
  composer require mixdinternet/articles
```

## Service Provider

Abra o arquivo `config/app.php` e adicione

`Mixdinternet\Articles\Providers\ArticlesServiceProvider::class`

## Migrations

```
  php artisan vendor:publish --provider="Mixdinternet\Articles\Providers\ArticlesServiceProvider" --tag="migrations"`
  php artisan migrate
```

## Configurações

É possivel a troca de icone e nomenclatura do pacote em `config/marticles.php`

```
  php artisan vendor:publish --provider="Mixdinternet\Articles\Providers\ArticlesServiceProvider" --tag="config"`
```