# Database SQL

## Instalação Inicial

Execute os arquivos na ordem numérica:

```bash
mysql -u root -p lrvweb < database/sql/001_schema.sql
mysql -u root -p lrvweb < database/sql/002_seed.sql
```

## Regras

1. **NUNCA modifique** arquivos `.sql` já criados
2. Para alterações no banco, crie uma **migration PHP** em `database/migrations/`
3. Migrations são executadas via `php cli/migrate.php run`

## Exemplo de Migration

Para adicionar uma coluna:

```bash
php cli/migrate.php create add_column_x_to_table_y
```

Isso gera um arquivo em `database/migrations/` onde você escreve o ALTER TABLE.
