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
