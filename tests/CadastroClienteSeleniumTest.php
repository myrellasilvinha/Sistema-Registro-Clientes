<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * Testes automatizados de ponta a ponta (end-to-end) do sistema de
 * Cadastro de Clientes, usando Selenium WebDriver.
 *
 * Pré-requisitos para executar:
 *  1. Selenium Server (ou Selenium Grid / chromedriver standalone)
 *     rodando em http://localhost:4444
 *  2. O sistema publicado em um servidor PHP + MySQL, com a URL base
 *     configurada na variável de ambiente BASE_URL
 *     (ex.: http://localhost/cadastro-clientes/)
 *  3. composer install (facebook/php-webdriver + phpunit)
 *
 * Execução:
 *  vendor/bin/phpunit tests/CadastroClienteSeleniumTest.php
 */
class CadastroClienteSeleniumTest extends TestCase
{
    private static RemoteWebDriver $driver;
    private static string $baseUrl;

    public static function setUpBeforeClass(): void
    {
        $seleniumUrl = getenv('SELENIUM_URL') ?: 'http://localhost:4444';
        self::$baseUrl = rtrim(getenv('BASE_URL') ?: 'http://localhost/cadastro-clientes/', '/') . '/';

        self::$driver = RemoteWebDriver::create(
            $seleniumUrl,
            DesiredCapabilities::chrome()
        );
    }

    public static function tearDownAfterClass(): void
    {
        self::$driver->quit();
    }

    /**
     * Cadastra um novo cliente e verifica se ele aparece na listagem
     * com a mensagem de sucesso. Retorna o nome gerado para ser
     * reaproveitado pelos testes seguintes.
     */
    public function testCadastrarCliente(): string
    {
        $driver = self::$driver;
        $nomeUnico = 'Cliente Teste ' . uniqid();

        $driver->get(self::$baseUrl . 'views/formCadastrarCliente.html');

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
            $driver->findElement(WebDriverBy::id('tabela-clientes'))->getText(),
            'O cliente recém-cadastrado deve aparecer na listagem'
        );

        return $nomeUnico;
    }

    /**
     * Edita o cliente criado no teste anterior, alterando o telefone,
     * e verifica que a alteração é refletida na listagem.
     *
     * @depends testCadastrarCliente
     */
    public function testEditarCliente(string $nomeCliente): string
    {
        $driver = self::$driver;

        $driver->get(self::$baseUrl . 'controladores/buscarClientes.php');

        $linhaCliente = $this->localizarLinhaPorNome($nomeCliente);
        $linhaCliente->findElement(WebDriverBy::className('link-editar'))->click();

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('form-editar'))
        );

        $campoTelefone = $driver->findElement(WebDriverBy::id('telefone'));
        $campoTelefone->clear();
        $novoTelefone = '68988887777';
        $campoTelefone->sendKeys($novoTelefone);

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
            $novoTelefone,
            $linhaAtualizada->findElement(WebDriverBy::className('col-telefone'))->getText()
        );

        return $nomeCliente;
    }

    /**
     * Exclui o cliente de teste e confirma que ele deixa de aparecer
     * na listagem.
     *
     * @depends testEditarCliente
     */
    public function testExcluirCliente(string $nomeCliente): void
    {
        $driver = self::$driver;

        $driver->get(self::$baseUrl . 'controladores/buscarClientes.php');

        $linhaCliente = $this->localizarLinhaPorNome($nomeCliente);
        $linhaCliente->findElement(WebDriverBy::className('link-excluir'))->click();

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('tabela-clientes'))
        );

        $this->assertStringContainsString(
            'Cliente excluído com sucesso',
            $driver->findElement(WebDriverBy::id('msg-sucesso'))->getText()
        );

        $this->assertStringNotContainsString(
            $nomeCliente,
            $driver->findElement(WebDriverBy::id('tabela-clientes'))->getText(),
            'O cliente excluído não deve mais aparecer na listagem'
        );
    }

    /**
     * Garante que o sistema valida campos obrigatórios: tentar salvar
     * um cliente sem nome deve exibir uma mensagem de erro e NÃO deve
     * cadastrá-lo.
     */
    public function testCadastroComNomeVazioExibeErro(): void
    {
        $driver = self::$driver;

        $driver->get(self::$baseUrl . 'views/formCadastrarCliente.html');

        $driver->findElement(WebDriverBy::id('nome'))->sendKeys('');
        $driver->findElement(WebDriverBy::id('email'))->sendKeys('semnome@teste.com');
        $driver->findElement(WebDriverBy::id('telefone'))->sendKeys('68999990000');
        $driver->findElement(WebDriverBy::id('btn-salvar'))->click();

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::tagName('body'))
        );

        $this->assertStringContainsString(
            'Dados inválidos',
            $driver->findElement(WebDriverBy::tagName('body'))->getText()
        );
    }

    /**
     * Procura, na tabela de clientes já carregada na página, a linha
     * (<tr>) cuja coluna "Nome" contém o texto informado.
     */
    private function localizarLinhaPorNome(string $nome)
    {
        $driver = self::$driver;
        $xpath = "//table[@id='tabela-clientes']//tr[td[contains(text(), \"{$nome}\")]]";

        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath($xpath))
        );

        return $driver->findElement(WebDriverBy::xpath($xpath));
    }
}
