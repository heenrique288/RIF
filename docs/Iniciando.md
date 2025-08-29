# Guia de Início Rápido

Este documento descreve como preparar o ambiente local para rodar o projeto em desenvolvimento.

---

## Pré-requisitos

Antes de começar, verifique se possui instalado em sua máquina:

- [PHP 8.x](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/) (ou outro banco suportado)

---

## Passo a passo para preparar o ambiente

1. **Clonar o repositório**

    ```bash
    git clone https://github.com/Calama-Devs/refeicoes.git
    cd refeicoes
    ```

2. **Instalar dependências PHP com Composer**

    ```bash
    composer install
    ```

3. **Configurar o banco de dados**

- Crie um banco no seu servidor local.

    ```bash
    php spark db:create refeicoes
    ```

- Execute as migrations para estruturar as tabelas:

    ```bash
    php spark migrate
    ```

4. **Configurar variáveis de ambiente**

- Copie o arquivo .env.example para .env.

    ```bash
    cp .env.example .env
    ```

- Edite as configurações de banco de dados e demais variáveis conforme necessário.

    ```bash
    # exemmplo de .env
    CI_ENVIRONMENT = development

    app.baseURL = 'http://localhost:8080'

    database.default.hostname = localhost
    database.default.database = refeicoes
    database.default.username = root
    database.default.password = 
    database.default.DBDriver = MySQLi
    database.default.DBPrefix = 
    database.default.port = 3306
    ```

5. **Rodar o servidor local**

    ```bash
    php spark serve
    ```

O projeto estará disponível em: http://localhost:8080

---

## Executando os testes

Para configurar o ambiente de testes, siga o guia em [docs/tests.md](https://github.com/Calama-Devs/refeicoes/blob/main/docs/TESTES.md).
E para rodar os testes automatizados, use:

```bash
vendor/bin/phpunit
```

Estrutura de diretórios (resumida)

```cpp
docs/          → Documentação do projeto
app/           → Código-fonte principal
tests/         → Testes automatizados
public/        → Pasta pública (entrypoint do projeto)
```

---

## Próximos passos

- Configurar os ambientes de desenvolvimento e produção de acordo com as variáveis do .env.
- Ler a documentação disponível na pasta [docs](https://github.com/Calama-Devs/refeicoes/tree/main/docs).

