# Onde Meu Dinheiro Foi?

Projeto inicial de controle financeiro pessoal feito com HTML, CSS, JavaScript, PHP e MySQL.

## O que já funciona
- Cadastro e login de usuários
- Painel com saldo, receitas e despesas
- Cadastro e exclusão de movimentações
- Categorias iniciais de receitas e despesas
- Histórico de movimentações
- Gráfico simples dos últimos meses
- Cadastro e acompanhamento de metas de economia

## Requisitos
- PHP 8.0 ou superior
- MySQL 5.7+ ou MariaDB
- Apache (o XAMPP pode fornecer Apache, PHP e MySQL)

## Como executar no XAMPP
1. Extraia a pasta `onde-meu-dinheiro-foi` dentro de `C:\xampp\htdocs\`.
2. Inicie Apache e MySQL pelo painel do XAMPP.
3. Abra `http://localhost/phpmyadmin`.
4. Importe o arquivo `database.sql` na aba **Importar**.
5. Se seu MySQL tiver usuário ou senha diferentes, altere `config/database.php`.
6. Acesse `http://localhost/onde-meu-dinheiro-foi/register.php`.
7. Crie uma conta e comece a cadastrar movimentações.

## Observações
- Os valores são armazenados por usuário; uma conta não acessa as movimentações de outra.
- As senhas são armazenadas com `password_hash`.
- As consultas usam PDO e parâmetros preparados.
- Este é um projeto inicial para estudo. Antes de publicar na internet, configure HTTPS, credenciais seguras, backups e outras proteções de produção.
