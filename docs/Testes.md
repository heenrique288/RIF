# Guia de Preparação do Ambiente de Testes

Este documento descreve como configurar o ambiente de testes para o projeto e executar os testes automatizados usando PHPUnit.

---

## 1. Instalar Dependências

Caso ainda não tenha feito, instale as dependências do projeto com **Composer**:

```bash
composer install
```

## 2. Configurar o PHPUnit

1. Copie o arquivo de configuração de exemplo:

```bash
cp phpunit.xml.dist phpunit.xml
```

2. Abra o arquivo **phpunit.xml** e descomente as linhas de variáveis de ambiente referentes ao banco de dados de teste. Edite conforme necessário:

```xml
<env name="database.tests.hostname" value="localhost" />
<env name="database.tests.database" value="teste" />
<env name="database.tests.username" value="root" />
<env name="database.tests.password" value="" />
<env name="database.tests.DBDriver" value="MySQLi" />
<env name="database.tests.DBPrefix" value="" />
```

⚠ Importante: É extremamente recomendado utilizar um banco de dados exclusivo para testes. Nunca rode os testes em produção ou em bancos que contenham dados reais, pois os testes podem alterar ou apagar informações.

## 3. Rodar os Testes

No terminal, dentro da raiz do projeto, execute:

```bash
vendor/bin/phpunit
```

Isso irá:
- executar todos os testes definidos na pasta **tests**.
- Gerar um resumo com testes aprovados, falhas e erros.
- Aplicar migrações e **refresh** do banco de testes, se configurado.

Para saída detalhada e depuração:

```bash
vendor/bin/phpunit --debug
```

## 4. Recomendações

- Mantenha o banco de testes isolado e limpo.
- Sempre verifique se as migrações estão atualizadas para que os testes rodem corretamente.
- Evite hardcode de senhas ou dados sensíveis nos arquivos de configuração.
- Utilize factories ou helpers para criar dados de teste rapidamente (ex.: usuários, cursos, etc.).
- Nunca rode os testes em ambientes de produção.

## 5. Estrutura de Pastas de Testes

- **tests/Feature** → Testes de rotas e fluxos completos.
- **tests/Unit** → Testes de unidades, como modelos e helpers.
- **tests/_support** → Helpers, mocks e configurações extras para testes.

## 6. Depuração

Durante os testes, você pode usar ferramentas de debug para inspecionar a resposta e a sessão:

```php
// Dump do corpo da resposta
$result->dumpBody();

// Dump das variáveis da view
$result->dumpViewVars();

// Dump da sessão
d(session()->get());
d(session()->getFlashdata());
```

Essas ferramentas ajudam a identificar problemas de autenticação, validação ou falhas de lógica nos testes.