# Dentix

Projeto Laravel 11 com Breeze.

## Instalação (Laragon)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run dev
php artisan serve
```

Depois de executar `php artisan serve`, abra o navegador em
[`http://localhost:8000`](http://localhost:8000) para visualizar a aplicação.

Se preferir rodar diretamente pelo aplicativo do Laragon, basta iniciar o
servidor e acessar [`http://dentix.test`](http://dentix.test) no menu **www** do
próprio Laragon.

### Erro "Unknown column 'horarios_funcionamento'"

Se, ao executar `php artisan migrate` ou `php artisan migrate:fresh`, ocorrer o
erro abaixo:

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'horarios_funcionamento'
```

verifique a pasta `database/migrations` e remova qualquer arquivo antigo chamado
`2024_01_01_000007_update_unidades_table_change_horarios_funcionamento_type.php`.
A coluna `horarios_funcionamento` foi substituída pela tabela `horarios` e essa
migration não é mais necessária.

### Erro "Field 'horarios_disponiveis' doesn't have a default value"

Se, ao criar uma cadeira, surgir o erro:

```
SQLSTATE[HY000]: General error: 1364 Field 'horarios_disponiveis' doesn't have a default value
```

significa que sua tabela `cadeiras` ainda possui a coluna `horarios_disponiveis`. Remova-a executando a migration
`2025_07_13_160000_remove_horarios_disponiveis_from_cadeiras_table.php` com `php artisan migrate`.

### Criando um usuário PostgreSQL

Para evitar usar a conta padrão `postgres`, você pode criar um usuário e um banco exclusivos para o Dentix. No terminal do PostgreSQL, execute:

```sql
CREATE USER dentix WITH PASSWORD 'senha';
CREATE DATABASE dentix OWNER dentix;
GRANT ALL PRIVILEGES ON DATABASE dentix TO dentix;
```

Em seguida, atualize o arquivo `.env` com as credenciais escolhidas:

```bash
DB_DATABASE=dentix
DB_USERNAME=dentix
DB_PASSWORD=senha
```

### Usuário administrador padrão

Se você executou `php artisan migrate:fresh` e perdeu seu cadastro, crie um usuário de acesso com:

```bash
php artisan db:seed --class=AdminUserSeeder
```


Caso o Artisan indique que a classe `AdminUserSeeder` não existe, execute:

```bash
composer dump-autoload
```

Isso recriará o autoloader do Composer e permitirá que a classe seja encontrada.

Ele gerará a conta `admin@example.com` com a senha `password`. Acesse com essas
credenciais e, se desejar, altere a senha após o login.

Esse seeder também cria o perfil **Administrador** com todas as permissões e o
associa automaticamente a esse usuário para que você já possa gerenciar novos
perfis e usuários.

## Deploy no Render

Para hospedar o Dentix no [Render](https://render.com), crie um novo **Web Service** apontando para este repositório.
Use os comandos abaixo para build e start do serviço.

### Comando de build
```bash
bash render-build.sh
```

O script `render-build.sh` instala PHP e Composer caso não estejam
presentes no ambiente, executa `composer install`, faz o build do
front-end com `npm run build` e aplica as migrações.

### Comando de start
```bash
php artisan serve --host 0.0.0.0 --port $PORT
```

### Variáveis de ambiente
Configure no painel do Render valores como:
```bash
APP_ENV=production
APP_URL=https://seu-servico.onrender.com
APP_KEY= # gerar localmente
DB_CONNECTION=pgsql
DB_HOST=<host do banco>
DB_PORT=<porta>
DB_DATABASE=<nome do banco>
DB_USERNAME=<usuario>
DB_PASSWORD=<senha>
```
Gere a chave de aplicação com
`php artisan key:generate --show` e copie o resultado
para a variável `APP_KEY` antes de criar o deploy.
