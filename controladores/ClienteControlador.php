<?php
require_once __DIR__ . '/../ClienteException.php';
require_once __DIR__ . '/../servicos/ClienteServico.php';
require_once __DIR__ . '/../DTOs/ClienteDTO.php';
require_once __DIR__ . '/../modelos/Cliente.php';
require_once __DIR__ . '/../session_safe.php';

class ClienteControlador
{
    public function salvar($clienteDTO)
    {
        try {
            $cliente = $this->converterParaModelo($clienteDTO);
            (new ClienteServico())->salvar($cliente);

            header('Location: ../views/mostrarClientes.php?msg=cadastrado');
            exit;
        } catch (PDOException $erro) {
            $this->redirecionarComErro(
                'Erro ao salvar no banco de dados. Verifique os dados e tente novamente.',
                '../views/formCadastrarCliente.php',
                $clienteDTO
            );
        } catch (ClienteException $erro) {
            $this->redirecionarComErro(
                $erro->getMessage(),
                '../views/formCadastrarCliente.php',
                $clienteDTO
            );
        }
    }

    public function editar($clienteDTO)
    {
        try {
            $cliente = $this->converterParaModelo($clienteDTO);
            $cliente->setId($clienteDTO->id);

            (new ClienteServico())->editar($cliente);

            unset($_SESSION['clienteEditar']);
            header('Location: ../views/mostrarClientes.php?msg=editado');
            exit;
        } catch (PDOException $erro) {
            $this->redirecionarComErroEdicao(
                'Erro ao atualizar no banco de dados. Verifique os dados e tente novamente.',
                $clienteDTO
            );
        } catch (ClienteException $erro) {
            $this->redirecionarComErroEdicao($erro->getMessage(), $clienteDTO);
        }
    }

    public function excluir($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT) || (int) $id <= 0) {
            throw new ClienteException('Cliente inválido para exclusão.');
        }

        (new ClienteServico())->excluir((int) $id);
    }

    public function criarModeloParaFormulario($clienteDTO)
    {
        return $this->converterParaModelo($clienteDTO);
    }

    private function converterParaModelo($clienteDTO)
    {
        $cliente = new Cliente();
        $cliente->setId($clienteDTO->id ?? null);
        $cliente->setNome(trim($clienteDTO->nome ?? ''));
        $cliente->setEmail(trim($clienteDTO->email ?? ''));
        $cliente->setTelefone(trim($clienteDTO->telefone ?? ''));
        $cliente->setDataNascimento($clienteDTO->data_nascimento ?: null);
        $cliente->setEndereco(trim($clienteDTO->endereco ?? ''));
        $cliente->setObservacoes(trim($clienteDTO->observacoes ?? ''));
        $cliente->setTags(trim($clienteDTO->tags ?? ''));
        $cliente->setStatus($clienteDTO->status ?: 'ativo');
        return $cliente;
    }

    private function redirecionarComErro($mensagem, $url, $clienteDTO)
    {
        $_SESSION['erro'] = $mensagem;
        $this->salvarDadosFormulario($clienteDTO);
        header("Location: {$url}");
        exit;
    }

    private function redirecionarComErroEdicao($mensagem, $clienteDTO)
    {
        $_SESSION['erro'] = $mensagem;
        $_SESSION['clienteEditar'] = $this->converterParaModelo($clienteDTO);
        $_SESSION['clienteEditar']->setId($clienteDTO->id);
        header('Location: ../views/formEditarCliente.php');
        exit;
    }

    private function salvarDadosFormulario($clienteDTO)
    {
        $_SESSION['form_nome'] = $clienteDTO->nome ?? '';
        $_SESSION['form_email'] = $clienteDTO->email ?? '';
        $_SESSION['form_telefone'] = $clienteDTO->telefone ?? '';
        $_SESSION['form_data_nascimento'] = $clienteDTO->data_nascimento ?? '';
        $_SESSION['form_endereco'] = $clienteDTO->endereco ?? '';
        $_SESSION['form_observacoes'] = $clienteDTO->observacoes ?? '';
    }
}
