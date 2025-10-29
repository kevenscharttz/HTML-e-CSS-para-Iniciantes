SHELL := /bin/bash

.PHONY: sail-up sail-down build test-artisan composer-install artisan

sail-up:
	@echo "Iniciando containers com docker-compose.sail.yml..."
	docker compose -f docker-compose.sail.yml up --build -d

sail-down:
	@echo "Parando e removendo containers..."
	docker compose -f docker-compose.sail.yml down -v

build:
	@echo "Build da imagem PHP"
	docker compose -f docker-compose.sail.yml build laravel.test

composer-install:
	@echo "Instalando dependências Composer dentro do container"
	docker compose -f docker-compose.sail.yml run --rm laravel.test composer install

artisan:
	@shift; docker compose -f docker-compose.sail.yml exec laravel.test php artisan "$@"

test-artisan:
	docker compose -f docker-compose.sail.yml exec laravel.test php artisan --version
