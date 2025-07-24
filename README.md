# MiniERP - Sistema de Gerenciamento de Pedidos

  Este é um projeto Laravel que implementa um sistema básico de gerenciamento de pedidos (MiniERP), incluindo carrinho, checkout, envio de e-mails de confirmação via SendGrid e webhooks para atualizações de status.

  ## Pré-requisitos

  - **Dependências**:
    - PHP 8.1 ou superior.
    - Composer (gerenciador de dependências PHP).
    - Node.js e NPM (para assets como `money-mask.js`).
    - MySQL 8.0 ou superior.

  ## Passo a Passo para Instalação

  ### 1. Clonar o Repositório
  Clone o repositório para sua máquina local:
  ```bash
  git clone https://github.com/jadyelsousa/mini-erp.git
  cd mini-erp
  ```

  ### 2. Instalar Dependências PHP
  Instale as dependências do Composer:
  ```bash
  composer install
  ```

  ### 3. Configurar o Ambiente
  - Copie o arquivo de exemplo de ambiente:
    ```bash
    cp .env.example .env
    ```
  - Edite o arquivo `.env` para configurar o banco de dados e o SendGrid:
    ```bash
    nano .env
    ```
  - Atualize as seguintes variáveis (exemplo):
    ```env
    APP_NAME=MiniERP
    APP_URL=http://localhost
    APP_ENV=local
    APP_DEBUG=true
    APP_KEY=base64:seu_app_key_aqui (gerar com `php artisan key:generate`)

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=minierp
    DB_USERNAME=seu_usuario_mysql
    DB_PASSWORD=sua_senha_mysql

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.sendgrid.net
    MAIL_PORT=587
    MAIL_USERNAME=apikey
    MAIL_PASSWORD=sua_chave_api_sendgrid
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="no-reply@minierp.com"
    MAIL_FROM_NAME="MiniERP"

    QUEUE_CONNECTION=sync
    ```
  - Gere a chave do aplicativo:
    ```bash
    php artisan key:generate
    ```

  ### 4. Configurar o Banco de Dados
  - Crie o banco de dados MySQL:
    ```bash
    mysql -u seu_usuario_mysql -p
    CREATE DATABASE minierp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    EXIT;
    ```
  - Rode as migrações para criar as tabelas:
    ```bash
    php artisan migrate
    ```

  ### 5. Popular o Banco com Seeders
  Insira dados iniciais (produtos, estoque, etc.):
  ```bash
  php artisan db:seed
  ```

  ### 6. Executar o Projeto
  Inicie o servidor Laravel:
  ```bash
  php artisan serve
  ```
  Acesse em: `http://localhost:8000`.

  ### 7. Testar Funcionalidades
  - **Carrinho e Checkout**: Acesse `http://localhost:8000/cart` e `http://localhost:8000/checkout`.
  - **E-mail de Confirmação**: Configure o SendGrid no `.env` e teste o checkout.
  - **Webhook**: Teste a rota `/webhook` com o comando:
    ```bash
    curl --request POST --url http://localhost:8000/api/webhook --header 'Content-Type: application/json' --header 'Accept: application/json' --data '{"order_id":1,"status":"approved"}'
    ```
    Ou use o Insomnia com o mesmo payload.

  ## Estrutura do Projeto
  - `app/Http/Controllers/`: Controladores como `CheckoutController` e `WebhookController`.
  - `database/migrations/`: Migrações para tabelas (ex.: `orders`, `order_items`).
  - `database/seeders/`: Seeders para dados iniciais.
  - `resources/views/`: Views como `checkout.index` e `emails/order_confirmation`.
  - `public/js/`: Arquivos JavaScript (ex.: `money-mask.js`).
  - `.env`: Configurações de ambiente.
