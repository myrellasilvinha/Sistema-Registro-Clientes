<?php

namespace Tests;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Framework\TestCase;

class CadastroClienteSeleniumTest extends TestCase
{
    private static RemoteWebDriver $driver;
    private static string $baseUrl;

    public static function setUpBeforeClass(): void
    {
        self::$baseUrl = rtrim(
            getenv('BASE_URL') ?: 'http://localhost/cadastro-clientes/',
            '/'
        ) . '/';

        $seleniumUrl = getenv('SELENIUM_URL') ?: 'http://localhost:4444';
        $navegador = strtolower(getenv('BROWSER') ?: 'chrome');
        $capabilities = $navegador === 'firefox'
            ? DesiredCapabilities::firefox()
            : DesiredCapabilities::chrome();

        self::$driver = RemoteWebDriver::create($seleniumUrl, $capabilities);

        self::login();
    }

    public static function tearDownAfterClass(): void
    {
        if (isset(self::$driver)) {
            self::$driver->quit();
        }
    }

    private static function login(): void
    {
        self::$driver->get(self::$baseUrl . 'views/login.php');
        self::$driver->findElement(WebDriverBy::id('username'))->sendKeys('admin');
        self::$driver->findElement(WebDriverBy::id('password'))->sendKeys('admin123');
        self::$driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();

        self::$driver->wait(10)->until(
            WebDriverExpectedCondition::urlContains('dashboard.php')
        );
    }

    public function testCadastrarCliente(): string
    {
        $driver = self::$driver;
        $nomeUnico = 'Cliente Teste ' . uniqid();

        $driver->get(self::$baseUrl . 'views/formCadastrarCliente.php');
        $driver->findElement(WebDriverBy::id('nome'))->sendKeys($nomeUnico);
        $driver->findElement(WebDriverBy::id('email'))->sendKeys('cliente' . time() . '@teste.com');
        $driver->findElement(WebDriverBy::id('telefone'))->sendKeys('68999990000');
        $driver->findElement(WebDriverBy::id('btn-salvar'))->click();

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('tabela-clientes'))
        );

        $this->assertStringContainsString(
            'Cliente cadastrado com sucesso',
            $driver->findElement(WebDriverBy::id('msg-sucesso'))->getText()
        );

        $this->assertStringContainsString(
            $nomeUnico,
            $driver->findElement(WebDriverBy::id('tabela-clientes'))->getText()
        );

        return $nomeUnico;
    }

    /** @depends testCadastrarCliente */
    public function testEditarCliente(string $nomeCliente): string
    {
        $driver = self::$driver;
        $driver->get(self::$baseUrl . 'views/mostrarClientes.php');

        $linhaCliente = $this->localizarLinhaPorNome($nomeCliente);
        $linhaCliente->findElement(WebDriverBy::className('link-editar'))->click();

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('form-editar'))
        );

        $campoTelefone = $driver->findElement(WebDriverBy::id('telefone'));
        $campoTelefone->clear();
        $campoTelefone->sendKeys('68988887777');
        $driver->findElement(WebDriverBy::id('btn-atualizar'))->click();

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('tabela-clientes'))
        );

        $this->assertStringContainsString(
            'Cliente atualizado com sucesso',
            $driver->findElement(WebDriverBy::id('msg-sucesso'))->getText()
        );

        $linhaAtualizada = $this->localizarLinhaPorNome($nomeCliente);
        $this->assertStringContainsString(
            '(68) 98888-7777',
            $linhaAtualizada->findElement(WebDriverBy::className('col-telefone'))->getText()
        );

        return $nomeCliente;
    }

    /** @depends testEditarCliente */
    public function testExcluirCliente(string $nomeCliente): void
    {
        $driver = self::$driver;
        $driver->get(self::$baseUrl . 'views/mostrarClientes.php');

        $linhaCliente = $this->localizarLinhaPorNome($nomeCliente);
        $linhaCliente->findElement(WebDriverBy::cssSelector('button.link-excluir'))->click();

        $driver->switchTo()->alert()->accept();
        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('tabela-clientes'))
        );

        $this->assertStringContainsString(
            'Cliente excluido com sucesso',
            $driver->findElement(WebDriverBy::id('msg-sucesso'))->getText()
        );
        $this->assertStringNotContainsString(
            $nomeCliente,
            $driver->findElement(WebDriverBy::id('tabela-clientes'))->getText()
        );
    }

    public function testValidacaoTelefoneInvalido(): void
    {
        $driver = self::$driver;
        $driver->get(self::$baseUrl . 'views/formCadastrarCliente.php');

        $driver->findElement(WebDriverBy::id('nome'))->sendKeys('Cliente Invalido ' . uniqid());
        $driver->findElement(WebDriverBy::id('email'))->sendKeys('invalido' . time() . '@teste.com');
        $driver->findElement(WebDriverBy::id('telefone'))->sendKeys('123');
        $driver->findElement(WebDriverBy::id('btn-salvar'))->click();

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('msg-erro'))
        );

        $this->assertStringContainsString(
            'Telefone deve ter DDD',
            $driver->findElement(WebDriverBy::id('msg-erro'))->getText()
        );
    }

    private function localizarLinhaPorNome(string $nomeCliente)
    {
        $xpath = "//table[@id='tabela-clientes']//tr[td[contains(normalize-space(), \"{$nomeCliente}\")]]";

        self::$driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath($xpath))
        );

        return self::$driver->findElement(WebDriverBy::xpath($xpath));
    }
}
