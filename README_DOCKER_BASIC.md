# Usando Docker sem Docker Compose

Se você tem apenas Docker básico instalado (sem Docker Compose), siga estas instruções para rodar o projeto.

## Opção 1: Instalar Docker Compose

Recomendamos instalar Docker Compose seguindo o guia em `README_INSTALL.md`.

## Opção 2: Usar apenas Docker (sem Compose)

Se preferir não instalar Docker Compose, você pode usar o `Dockerfile.standalone`:

```bash
# 1. Build da imagem
docker build -t meu-laravel-app -f Dockerfile.standalone .

# 2. Criar volume para persistência
docker volume create laravel-storage

# 3. Rodar container
docker run -d \
  --name meu-laravel-app \
  -p 8000:80 \
  -v laravel-storage:/var/www/html/storage \
  -e APP_ENV=local \
  -e APP_DEBUG=true \
  -e APP_KEY=base64:sua_chave_aqui \
  -e DB_CONNECTION=sqlite \
  meu-laravel-app

# 4. Gerar app key (se não tiver)
docker exec meu-laravel-app php artisan key:generate --show

# 5. Rodar migrações
docker exec meu-laravel-app php artisan migrate --seed --force

# 6. Ver logs (se precisar)
docker logs -f meu-laravel-app
```

Acesse http://localhost:8000

### Comandos úteis (Docker básico)

```bash
# Parar container
docker stop meu-laravel-app

# Iniciar container parado
docker start meu-laravel-app

# Remover container
docker rm -f meu-laravel-app

# Executar comando artisan
docker exec meu-laravel-app php artisan COMANDO

# Ver logs
docker logs -f meu-laravel-app

# Entrar no container (shell)
docker exec -it meu-laravel-app bash
```

### Diferenças para o Docker Compose

O `Dockerfile.standalone`:
- Usa Apache embutido (sem Nginx separado)
- Não inclui MySQL/Redis/MailHog
- SQLite por padrão (mais simples)
- Tudo em um único container

Para desenvolvimento completo, recomendamos instalar Docker Compose e usar o fluxo padrão.