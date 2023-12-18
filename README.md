# Laravel Pagarme
<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-1-orange.svg?style=flat-square)](#contributors-)
<!-- ALL-CONTRIBUTORS-BADGE:END -->

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
<table>
  <tbody>
    <tr>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/ThalisGaetaCorbee"><img src="https://avatars.githubusercontent.com/u/117653545?v=4?s=100" width="100px;" alt="Thalis Gaeta"/><br /><sub><b>Thalis Gaeta</b></sub></a><br /><a href="https://github.com/wallisonfelipe/laravel-pagar-me/commits?author=ThalisGaetaCorbee" title="Code">💻</a></td>
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->
