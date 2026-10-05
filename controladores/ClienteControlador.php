<?php
require_once __DIR__ . "/../ClienteException.php";
require_once __DIR__ . "/../servicos/ClienteServico.php";
require_once __DIR__ . "/../DTOs/ClienteDTO.php";
require_once __DIR__ . "/../modelos/Cliente.php";

class ClienteControlador {

    public function salvar($clienteDTO) {
        try {
            $cliente = $this->converterParaModelo($clienteDTO);

            $clienteServico = new ClienteServico();
            $clienteServico->salvar($cliente);

            header('Location: ../controladores/buscarClientes.php?msg=cadastrado');
            exit;

        } catch (PDOException $erro) {
            $this->redirecionarComErro(
                "Erro ao salvar no banco de dados. Tente novamente.",
                "../views/formCadastrarCliente.php",
                $clienteDTO
            );
        } catch (ClienteException $erro) {
            $this->redirecionarComErro(
                $erro->getMessage(),
                "../views/formCadastrarCliente.php",
                $clienteDTO
            );
        }
    }

    public function editar($clienteDTO) {
        try {
            $cliente = $this->converterParaModelo($clienteDTO);
            $cliente->setId($clienteDTO->id);

            $clienteServico = new ClienteServico();
            $clienteServico->editar($cliente);

            header('Location: ../controladores/buscarClientes.php?msg=editado');
            exit;

        } catch (PDOException $erro) {
            $this->redirecionarComErroEdicao(
                "Erro ao atualizar no banco de dados. Tente novamente.",
                $clienteDTO
            );
        } catch (ClienteException $erro) {
            $this->redirecionarComErroEdicao(
                $erro->getMessage(),
                $clienteDTO
            );
        }
    }

    public function excluir($id) {
        $clienteServico = new ClienteServico();
        $clienteServico->excluir($id);
    }

    private function converterParaModelo($clienteDTO) {
        $cliente = new Cliente();
        $cliente->setNome($clienteDTO->nome);
        $cliente->setEmail($clienteDTO->email);
        $cliente->setTelefone($clienteDTO->telefone);
        return $cliente;
    }

    private function redirecionarComErro($mensagem, $url, $clienteDTO) {
        session_start();
        $_SESSION['erro'] = $mensagem;
        $_SESSION['form_nome'] = $clienteDTO->nome ?? '';
        $_SESSION['form_email'] = $clienteDTO->email ?? '';
        $_SESSION['form_telefone'] = $clienteDTO->telefone ?? '';
        header("Location: $url");
        exit;
    }

    private function redirecionarComErroEdicao($mensagem, $clienteDTO) {
        session_start();
        $_SESSION['erro'] = $mensagem;

        // Mantém o cliente na sessão de edição com os dados tentados
        $cliente = $this->converterParaModelo($clienteDTO);
        $cliente->setId($clienteDTO->id);
        $_SESSION['clienteEditar'] = $cliente;

        header("Location: ../views/formEditarCliente.php");
        exit;
    }
}

?>
