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
