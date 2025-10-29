# NewURL Project Setup with Docker

Este guia irá ajudá-lo a configurar e executar o projeto NewURL usando Docker e Docker Compose. Isso garante que você não precise instalar PHP, Composer, Node.js, Nginx ou MySQL diretamente em sua máquina, apenas o Docker.

## Pré-requisitos

Certifique-se de ter o Docker e o Docker Compose instalados em sua máquina. Abaixo estão as instruções para os sistemas operacionais mais comuns:

### macOS

A maneira mais fácil de instalar o Docker no macOS é usando o Docker Desktop.

1.  **Baixe o Docker Desktop:**
    *   Vá para [Docker Desktop para Mac](https://docs.docker.com/desktop/install/mac-install/) e baixe o instalador.
2.  **Instale o Docker Desktop:**
    *   Abra o arquivo `.dmg` baixado e arraste o ícone do Docker para a pasta Aplicativos.
    *   Inicie o Docker Desktop a partir da pasta Aplicativos.
    *   Siga as instruções na tela para concluir a instalação.

### Windows

A maneira mais fácil de instalar o Docker no Windows é usando o Docker Desktop.

1.  **Baixe o Docker Desktop:**
    *   Vá para [Docker Desktop para Windows](https://docs.docker.com/desktop/install/windows-install/) e baixe o instalador.
2.  **Instale o Docker Desktop:**
    *   Execute o instalador e siga as instruções. Certifique-se de que o WSL 2 (Windows Subsystem for Linux 2) esteja ativado, pois é o método recomendado para o Docker Desktop no Windows.
    *   Inicie o Docker Desktop após a instalação.

### Linux

Para Linux, você pode instalar o Docker Engine e o Docker Compose separadamente. As instruções podem variar ligeiramente dependendo da sua distribuição.

#### Ubuntu / Debian

1.  **Atualize os pacotes e instale as dependências:**
    ```bash
    sudo apt-get update
    sudo apt-get install ca-certificates curl gnupg
    ```
2.  **Adicione a chave GPG oficial do Docker:**
    ```bash
    sudo install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    sudo chmod a+r /etc/apt/keyrings/docker.gpg
    ```
3.  **Adicione o repositório do Docker ao APT sources:**
    ```bash
    echo \
      "deb [arch=\"$(dpkg --print-architecture)\" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
      \"$(. /etc/os-release && echo \"$VERSION_CODENAME\")\" stable" | \
      sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
    sudo apt-get update
    ```
4.  **Instale o Docker Engine e Docker Compose:**
    ```bash
    sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    ```
5.  **Adicione seu usuário ao grupo `docker` (opcional, para não usar `sudo`):**
    ```bash
    sudo usermod -aG docker $USER
    newgrp docker
    ```

#### Fedora

1.  **Atualize os pacotes:**
    ```bash
    sudo dnf -y update
    ```
2.  **Instale o Docker Engine e Docker Compose:**
    ```bash
    sudo dnf -y install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    ```
3.  **Inicie e habilite o serviço Docker:**
    ```bash
    sudo systemctl start docker
    sudo systemctl enable docker
    ```
4.  **Adicione seu usuário ao grupo `docker` (opcional, para não usar `sudo`):**
    ```bash
    sudo usermod -aG docker $USER
    newgrp docker
    ```

#### CentOS / RHEL

1.  **Atualize os pacotes e instale as dependências:**
    ```bash
    sudo yum install -y yum-utils
    sudo yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
    ```
2.  **Instale o Docker Engine e Docker Compose:**
    ```bash
    sudo yum install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    ```
3.  **Inicie e habilite o serviço Docker:**
    ```bash
    sudo systemctl start docker
    sudo systemctl enable docker
    ```
4.  **Adicione seu usuário ao grupo `docker` (opcional, para não usar `sudo`):**
    ```bash
    sudo usermod -aG docker $USER
    newgrp docker
    ```

Após a instalação, você pode verificar se o Docker está funcionando corretamente executando:

```bash
docker run hello-world
```

E para o Docker Compose (se instalado separadamente):

```bash
docker compose version
```

## Configuração do Projeto

Siga os passos abaixo para colocar o projeto em funcionamento:

1.  **Clone o Repositório (se ainda não o fez):**

    ```bash
    git clone <URL_DO_SEU_REPOSITORIO>
    cd newUrl-main-main # Ou o nome da pasta do seu projeto
    ```

2.  **Variáveis de Ambiente:**

    Um arquivo `.env` já foi criado para você com as configurações básicas para o Docker. Se precisar de configurações específicas, edite este arquivo.

    ```ini
    APP_NAME="NewUrl"
    APP_ENV=local
    APP_KEY=
    APP_DEBUG=true
    APP_URL=http://localhost:8000

    LOG_CHANNEL=stack
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=newurl_db
    DB_USERNAME=db_user
    DB_PASSWORD=db_password

    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    FILESYSTEM_DISK=local
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=file
    SESSION_LIFETIME=120

    MEMCACHED_HOST=127.0.0.1

    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    MAIL_MAILER=smtp
    MAIL_HOST=mailpit
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"

    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    AWS_USE_PATH_STYLE_ENDPOINT=false

    PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    ```

3.  **Construir e Iniciar os Contêineres Docker:**

    Na raiz do projeto (onde estão `docker-compose.yml` e `Dockerfile`), execute o seguinte comando para construir as imagens e iniciar os serviços:

    ```bash
    docker-compose up --build -d
    ```

    *   `--build`: Reconstrói as imagens Docker. Use isso sempre que fizer alterações no `Dockerfile` ou se for a primeira vez que está subindo o projeto.
    *   `-d`: Inicia os contêineres em segundo plano (detached mode).

4.  **Gerar a Chave da Aplicação (se `APP_KEY` estiver vazio no `.env`):**

    Se a variável `APP_KEY` no seu arquivo `.env` estiver vazia, você precisará gerá-la. O `Dockerfile` já tenta fazer isso, mas se houver algum problema ou se você estiver subindo o projeto pela primeira vez sem ter a chave, pode executar manualmente:

    ```bash
    docker-compose exec app php artisan key:generate
    ```

5.  **Executar as Migrações do Banco de Dados:**

    Para criar as tabelas no banco de dados, execute as migrações:

    ```bash
    docker-compose exec app php artisan migrate
    ```

6.  **Acessar a Aplicação:**

    Após todos os passos, a aplicação estará disponível em seu navegador no seguinte endereço:

    ```
    http://localhost:8000
    ```

## Comandos Úteis do Docker Compose

*   **Parar os contêineres:**
    ```bash
    docker-compose stop
    ```

*   **Parar e remover os contêineres, redes e volumes (cuidado com os dados do banco de dados!):**
    ```bash
    docker-compose down
    ```

*   **Ver os logs dos serviços:**
    ```bash
    docker-compose logs -f
    ```

*   **Executar um comando dentro do contêiner da aplicação (ex: `bash`):**
    ```bash
    docker-compose exec app bash
    ```

*   **Executar o seeder do banco de dados (ex: para popular dados iniciais):**
    ```bash
    docker-compose exec app php artisan db:seed
    ```

## Solução de Problemas Comuns

*   **"Address already in use" ou porta 8000 já em uso:** Certifique-se de que nenhuma outra aplicação esteja usando a porta 8000 em sua máquina. Você pode alterar a porta no `docker-compose.yml` se necessário.
*   **Erros de conexão com o banco de dados:** Verifique se as variáveis `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD` no seu arquivo `.env` correspondem às configurações no `docker-compose.yml`.
*   **Assets de frontend não carregando:** Certifique-se de que o `npm install` e `npm run build` foram executados com sucesso no `Dockerfile` ou execute-os manualmente dentro do contêiner da aplicação:
    ```bash
    docker-compose exec app npm install
    docker-compose exec app npm run build
    ```

Se você encontrar outros problemas, verifique os logs dos contêineres com `docker-compose logs -f` para obter mais informações.
