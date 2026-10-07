# Sistema de Cadastro de Clientes — Trabalho N1

Sistema web em PHP com arquitetura em camadas (**Modelo / DTO / DAO / Serviço / Controlador / View**) e CRUD completo de clientes: **cadastrar, editar, excluir e buscar**.

## Estrutura

```text
cadastro-clientes/
├── index.php
├── conexao.php
├── clientes.sql
├── clientes_update.sql
├── ClienteException.php
├── modelos/Cliente.php
├── DTOs/ClienteDTO.php
├── DAOs/ClienteDAO.php
├── servicos/ClienteServico.php
├── controladores/
│   ├── rota.php
│   ├── ClienteControlador.php
│   ├── buscarClientes.php
│   ├── editarCliente.php
│   ├── excluirCliente.php
│   └── exportarClientes.php
├── views/
│   ├── login.php
│   ├── dashboard.php
│   ├── formCadastrarCliente.php
│   ├── formEditarCliente.php
│   └── mostrarClientes.php
├── css/style.css
└── tests/CadastroClienteSeleniumTest.php
```

## Banco de dados

1. Execute `clientes.sql` para criar o banco `cadastro_clientes` e a tabela completa.
2. Se você já tinha a versão antiga do banco, execute `clientes_update.sql` para migrar a tabela.
3. Configure o acesso ao MySQL no `.env`:

```env
DB_HOST=localhost
DB_NAME=cadastro_clientes
DB_USER=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

## Executando

Coloque o projeto dentro do `htdocs` do XAMPP ou em outro servidor PHP/Apache. Depois acesse:

```text
http://localhost/cadastro-clientes/
```

O sistema inicia na tela de login.

**Usuário de demonstração:** `admin`  
**Senha de demonstração:** `admin123`

## Selenium

Instale as dependências:

```bash
composer install
```

Inicie o Selenium Server em `http://localhost:4444` e mantenha o Apache/MySQL funcionando.

Execute:

```bash
BASE_URL=http://localhost/cadastro-clientes/ vendor/bin/phpunit tests/CadastroClienteSeleniumTest.php
```

No Windows PowerShell:

```powershell
$env:BASE_URL="http://localhost/cadastro-clientes/"
vendor/bin/phpunit tests/CadastroClienteSeleniumTest.php
```

Para rodar no Firefox, defina `BROWSER=firefox` (o padrão é Chrome):

```bash
BROWSER=firefox BASE_URL=http://localhost/cadastro-clientes/ vendor/bin/phpunit tests/CadastroClienteSeleniumTest.php
```

Os testes cobrem login, cadastro, edição, exclusão e validação de telefone.

### Selenium IDE (extensão do navegador)

O arquivo `tests/selenium-ide/cadastro-clientes.side` pode ser aberto no Selenium IDE (File > Open Project). Ao rodar, informe como Base URL a pasta onde o projeto está no seu servidor (por exemplo `http://localhost/Sistema-Registro-Clientes/`, com a barra no final); os passos usam caminhos relativos a ela.

Atenção: no Firefox atual (93 ou superior) o comando `type` falha com `initKeyEvent is not a function`, porque esse método foi removido do navegador. No Selenium IDE o `sendKeys` também usa esse método e falha do mesmo jeito. O arquivo `.side` preenche os campos com `executeScript` (define o `value` e dispara os eventos `input` e `change`). Para digitação real, rode o teste PHP com `BROWSER=firefox`.
