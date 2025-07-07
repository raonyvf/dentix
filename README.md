# Dentix

Core do sistema SaaS odontológico utilizando Laravel 11.

## Requisitos
- PHP 8.2+
- Composer
- MySQL (Laragon)
- Node.js + NPM
- Laravel instalado globalmente (opcional)

## Passo a passo (Laragon - Windows)
1. Clone ou extraia o projeto em `C:\laragon\www\dentix`
2. Acesse o terminal do Laragon e vá para a pasta do projeto:
   ```bash
   cd C:\laragon\www\dentix
   ```
3. Instale as dependências do Composer e NPM:
   ```bash
   composer install
   npm install && npm run build
   ```
4. Copie o arquivo `.env.example` para `.env` e configure o banco de dados MySQL.
5. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```
6. Execute as migrações:
   ```bash
   php artisan migrate
   ```
7. Inicie o servidor de desenvolvimento:
   ```bash
   php artisan serve
   ```
8. Acesse `http://localhost:8000` no navegador.

O painel administrativo pode ser acessado em `/admin` após autenticação.
