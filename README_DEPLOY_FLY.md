# Deploy no Fly.io — guia passo a passo (PT-BR)

Este documento explica como publicar este projeto Laravel no Fly.io usando o `Dockerfile` presente no repositório (ou usando a imagem construída a partir dele). Inclui também notas sobre banco de dados (migrar de SQLite para Postgres) e variáveis de ambiente necessárias.

Resumo da abordagem
- Usaremos `flyctl` para criar a aplicação e gerenciar recursos (Postgres, volumes, secrets).
- O `fly.toml` na raiz (pré-criado) contém um `release_command` que roda `php artisan migrate --force && php artisan storage:link` durante cada deploy.

Pré-requisitos (no seu computador)
- Conta Fly.io (https://fly.io)
- `flyctl` instalado (https://fly.io/docs/hands-on/install-flyctl/)
- Git configurado e repositório remoto (recomendado)
- Docker instalado localmente (opcional, usado para testes locais)

1) Login e inicialização

```bash
# autentica sua conta fly
flyctl auth login

# posicionar-se na raiz do repositório
cd /caminho/para/seu/projeto
```

2) Criar a app Fly

Execute (substitua NOME_APP por um identificador único):

```bash
flyctl apps create NOME_APP --region gru
```

Isso criará um `fly.toml` local (se não existia). Se você já tem `fly.toml` (o que entregamos), abra-o e ajuste `app = "REPLACE_WITH_YOUR_APP_NAME"` para o nome criado.

3) Provisionar Postgres gerenciado (recomendado em produção)

Você pode criar um Postgres via Fly e conectar ao app.

```bash
flyctl postgres create --name NOME_APP-db --region gru

# após criação, pegue a DATABASE_URL exibida e exporte como secret
```

4) Definir secrets importantes

Gere uma APP_KEY localmente (se tiver PHP/Composer):

```bash
# dentro do container local (ou localmente) - apenas para gerar key
php artisan key:generate --show
# copie o valor retornado (ex.: base64:...)
```

Defina secrets no Fly (substitua os valores):

```bash
flyctl secrets set APP_KEY="base64:SUACHAVE" \
  APP_ENV=production APP_DEBUG=false \
  DATABASE_URL="postgres://user:pass@host:5432/dbname" \
  FILESYSTEM_DRIVER=local
```

Observação: se usar S3/Spaces para storage, defina `FILESYSTEM_DRIVER=s3` e as chaves S3 correspondentes.

5) Criar volume de storage (opcional, recomendado para uploads)

```bash
flyctl volumes create storage --region gru --size 3
```

O `fly.toml` já tem um `[[mounts]]` apontando `storage` para `/var/www/html/storage` — isso fará com que o storage seja persistente.

6) Deploy

```bash
# build + deploy (flyctl usa o Dockerfile local para criar a imagem)
flyctl deploy --remote-only
```

Observações sobre `--remote-only`: faz o build na infraestrutura do Fly em vez da sua máquina local. Se preferir construir localmente, remova a flag.

7) Verificar logs e status

```bash
flyctl status --app NOME_APP
flyctl logs --app NOME_APP
```

8) Mudar de SQLite para Postgres (nota importante)

O repositório usa SQLite em desenvolvimento. Para produção com Postgres:

- Atualize `.env`/secrets para usar `DATABASE_URL` apontando para o Postgres gerenciado.
- Verifique se migrations funcionam sem erros: rodar localmente `php artisan migrate` antes do deploy pode ajudar.
- Caso existam seeds ou dados locais, exporte/importe conforme necessário.

9) GitHub Actions / CI (opcional)

Você pode automatizar deploys com GitHub Actions. Em resumo:

- Criar secret `FLY_API_TOKEN` no repo (Settings > Secrets) contendo um token de deploy Fly.
- Criar workflow que roda `flyctl deploy --remote-only` usando `FLY_API_TOKEN`.

Exemplo mínimo (coloque em `.github/workflows/deploy-fly.yml`):

```yaml
name: Deploy to Fly

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Install flyctl
        run: curl -L https://fly.io/install.sh | sh
      - name: Deploy
        env:
          FLY_API_TOKEN: ${{ secrets.FLY_API_TOKEN }}
        run: |
          export PATH="$HOME/.fly/bin:$PATH"
          flyctl deploy --remote-only --app NOME_APP
```

10) Troubleshooting rápido

- Se `release_command` falhar, o deploy marca erro. Veja logs (`flyctl logs`) e corrija. Você pode desabilitar temporariamente `release_command` no `fly.toml` para testar.
- Erros de permissões em storage: verifique mounts e dono dos arquivos. Ajuste o `entrypoint` para rodar `chown -R www-data:www-data storage bootstrap/cache` se necessário.

Conclusão e próximos passos

- Ajuste `fly.toml` substituindo `REPLACE_WITH_YOUR_APP_NAME` pelo nome da app criada.
- Crie secrets (`APP_KEY`, `DATABASE_URL`, chaves S3 se for usar) antes de fazer o primeiro deploy.
- Se quiser, posso:
  - criar o workflow do GitHub Actions diretamente no repositório;
  - adaptar o `fly.toml` para utilizar uma imagem já construída (registry) em vez de build automático;
  - ou rodar uma checagem local de build Docker (se permitir executar `docker build` aqui).

FIM
