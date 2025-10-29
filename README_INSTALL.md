# Guia de Instalação (Docker e Docker Compose)

Este guia explica como instalar Docker e Docker Compose em diferentes sistemas operacionais.

## Ubuntu/Debian (forma mais fácil)

```bash
# Remover versões antigas (se existirem)
sudo apt-get remove docker docker-engine docker.io containerd runc || true

# Instalar dependências
sudo apt-get update
sudo apt-get install -y \
    ca-certificates \
    curl \
    gnupg \
    lsb-release

# Adicionar chave GPG oficial do Docker
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

# Configurar repositório
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Instalar Docker Engine e Docker Compose
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# Adicionar seu usuário ao grupo docker (evita precisar sudo)
sudo usermod -aG docker $USER

# IMPORTANTE: fazer logout e login novamente para as mudanças de grupo terem efeito
# ou execute:
newgrp docker

# Testar instalação
docker run hello-world
docker compose version
```

## Windows

1. Baixe e instale o Docker Desktop para Windows:
   https://www.docker.com/products/docker-desktop

2. Durante a instalação:
   - Se perguntado, instale o WSL 2 (Windows Subsystem for Linux)
   - Habilite a integração com WSL 2

3. Após instalar:
   - Inicie o Docker Desktop
   - Aguarde o ícone do Docker ficar estável na barra de tarefas
   - Abra um terminal (PowerShell ou CMD) e teste:
     ```
     docker --version
     docker compose version
     ```

## macOS

1. Baixe e instale o Docker Desktop para Mac:
   https://www.docker.com/products/docker-desktop

2. Após instalar:
   - Abra o Docker Desktop
   - Aguarde o ícone do Docker ficar estável na barra de menus
   - Abra um terminal e teste:
     ```
     docker --version
     docker compose version
     ```

## Outros sistemas Linux (método manual)

Se você usa outro sistema Linux ou prefere instalar manualmente:

1. Instale o Docker Engine seguindo a documentação oficial para sua distribuição:
   https://docs.docker.com/engine/install/

2. Instale o Docker Compose:
```bash
# Baixar Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

# Tornar executável
sudo chmod +x /usr/local/bin/docker-compose

# Testar
docker-compose --version
```

## Verificação da instalação

Após instalar, você pode verificar se tudo está funcionando:

```bash
# Deve mostrar a versão do Docker
docker --version

# Deve mostrar a versão do Compose
docker compose version

# Testa se consegue baixar e rodar containers
docker run hello-world
```

## Problemas comuns

1. "Cannot connect to the Docker daemon"
   - Verifique se o serviço está rodando:
     ```bash
     sudo systemctl start docker
     ```
   - Em Windows/Mac: verifique se o Docker Desktop está aberto e rodando

2. "Permission denied"
   - Adicione seu usuário ao grupo docker:
     ```bash
     sudo usermod -aG docker $USER
     ```
   - Faça logout e login novamente

3. WSL 2 não instalado (Windows)
   - Abra PowerShell como administrador e execute:
     ```powershell
     wsl --install
     ```

4. Erro de memória ou CPU
   - No Docker Desktop, ajuste os recursos em Settings > Resources

## Próximos passos

Após instalar Docker e Docker Compose:

1. Clone este repositório
2. Siga as instruções em `README_DOCKER.md` para rodar o projeto

Se precisar desinstalar:
```bash
# Ubuntu/Debian
sudo apt-get purge docker-ce docker-ce-cli containerd.io docker-compose-plugin
sudo rm -rf /var/lib/docker
sudo rm -rf /var/lib/containerd
```

Para Windows/Mac: desinstale o Docker Desktop normalmente pelo painel de controle/aplicativos.