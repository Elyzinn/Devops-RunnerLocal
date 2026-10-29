# CRUD de catálogo de jogos

Aplicação web para cadastrar e consultar jogos. Cada registro possui nome, produtora, gênero, quantidade e preço. O CRUD permite listar, cadastrar, editar e excluir jogos.

## Tecnologias

- PHP 8.3 com Apache, executado em um container Docker.
- PostgreSQL 15.3 para armazenar os jogos.
- Docker Compose para configurar e iniciar os containers da aplicação e do banco de dados.
- Bootstrap 5.3.8 para os estilos da interface.

## Execução e acesso na VM

Na VM, a partir da pasta do projeto, inicie ou atualize os containers com:

```bash
docker compose up -d --build
```

Para consultar o IP da VM, use um dos comandos:

```bash
ip a
```

ou:

```bash
hostname -I
```

Em um navegador de um computador conectado à rede do laboratório, acesse:

```text
http://IP_DA_VM:8080/php/index.php
```

A porta `8080` da VM é encaminhada para a porta `80` do container da aplicação, conforme definido no `docker-compose.yml`.

## Runner local e deploy automático

O workflow do GitHub Actions está configurado para executar em um Runner self-hosted (`runs-on: self-hosted`). Para o deploy acontecer, esse Runner precisa estar disponível no ambiente que executará os comandos — neste projeto, a VM.

Quando há um `push` para a branch `main`, o GitHub Actions envia o job ao Runner. O workflow então:

1. Baixa o código do repositório.
2. Executa `docker compose up -d --build` para construir e iniciar ou atualizar os containers.
3. Executa `docker image prune -f` para remover imagens Docker antigas que não estão em uso.

Assim, após o Runner executar o workflow com sucesso, a aplicação fica disponível pelo IP da VM na porta `8080`.

## Alunos

**Elienay Henrique da Silva Souza**  
R.A.: 250283

**Lucas Mateus Galdino**  
R.A.: 250272
