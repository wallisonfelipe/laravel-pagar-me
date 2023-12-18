# Laravel Pagarme

Esse pacote é apenas um Wrapper não oficial das API's da plataforma Pagar.me


## Instalação

Instale laravel-pagar-me com composer

```bash
  composer require wallisonfelipe/laravel-pagar-me
```


Para publicar as migrations use 
```bash
  php artisan vendor:publish --tag=laravel-pagarme-migrations
```


Para publicar as controllers use 
```bash
  php artisan vendor:publish --tag=laravel-pagarme-controllers
```

Para usar o módulo PagarmeClient, é necessário ter em sua env a variável "PAGARME_SECRET_KEY"

```bash
  PAGARME_SECRET_KEY=test_sua_key
```


## Contributors

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->
