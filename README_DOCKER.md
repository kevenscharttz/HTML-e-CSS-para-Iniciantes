# Docker (desenvolvimento) — newUrl-main

Instruções em Português para rodar o projeto Laravel com SQLite usando Docker.

## Objetivo

Configurar um ambiente de desenvolvimento com PHP-FPM + Nginx e usar SQLite (sem serviço externo de banco). O container `app` roda PHP-FPM; o `nginx` faz proxy para ele.

## Arquivos adicionados

- `Dockerfile` — imagem PHP-FPM com extensões necessárias e Composer.
- `docker-compose.yml` — define serviços `app` e `nginx` (porta 8000).
- `docker/nginx/default.conf` — configuração do Nginx.
- `docker/entrypoint.sh` — script que garante permissões, cria `database.sqlite` e instala dependências quando necessário.
- `.dockerignore` — evita copiar arquivos grandes/sensíveis para o build.

## Antes de rodar

Este repositório contém duas opções de Docker:

- Fluxo atual (simples) usando `Dockerfile` e `docker-compose.yml` — otimizado para desenvolvimento com SQLite e Nginx.
- Nova opção baseada em um fluxo semelhante ao Laravel Sail (arquivo adicional `docker-compose.sail.yml` e `docker/sail/Dockerfile`) — recomendada quando quiser usar MySQL, Redis e um fluxo mais parecido com Sail.

Escolha uma das opções abaixo.

Opção rápida (Sail-like, recomendada para desenvolvimento com serviços):

1. Garanta que você tenha `docker` e `docker compose` instalados.
2. Suba os containers com o arquivo Sail-like:

```bash
make sail-up
```

3. (Opcional) Instale dependências Composer dentro do container e gere a APP_KEY:

```bash
make composer-install
make test-artisan
docker compose -f docker-compose.sail.yml exec laravel.test php artisan key:generate --show
```

4. Rodar migrações:

```bash
docker compose -f docker-compose.sail.yml exec laravel.test php artisan migrate --seed --force
```

5. A aplicação ficará disponível em `http://localhost:8000`.

Opção legacy (SQLite com Nginx): siga as instruções já presentes neste arquivo (mantidas abaixo).

## Como rodar

1. Construir e subir os containers:

```bash
docker compose up --build -d
```

2. Gerar chave de aplicação e rodar migrações:

```bash
docker compose exec app sh -c "php artisan key:generate && php artisan migrate --seed"
```

3. Abrir no navegador:

```
http://localhost:8000
```

## Permissões

Se tiver problemas com permissões (arquivos criados pelo host), execute:

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache database
```

## Observações

- O entrypoint tenta rodar `composer install` se `vendor/autoload.php` não existir. Como o código é montado via volume, isso facilita o primeiro uso em máquinas de desenvolvimento.
- Se preferir um fluxo onde `vendor` fica dentro da imagem, remova o volume `./:/var/www` do `docker-compose.yml` e adapte conforme necessário.

Se quer, eu posso:
- adicionar um `Makefile` com comandos úteis;
- adicionar um `docker-compose.override.yml` para testes; ou
- ajustar para usar MySQL/Postgres se depois quiser remover o SQLite.
