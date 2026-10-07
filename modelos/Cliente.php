<?php
class Cliente {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $data_cadastro;
    private $data_nascimento;
    private $endereco;
    private $observacoes;
    private $tags;
    private $status;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }

    public function getDataCadastro() { return $this->data_cadastro; }
    public function setDataCadastro($data_cadastro) { $this->data_cadastro = $data_cadastro; }

    public function getDataNascimento() { return $this->data_nascimento; }
    public function setDataNascimento($data_nascimento) { $this->data_nascimento = $data_nascimento; }

    public function getEndereco() { return $this->endereco; }
    public function setEndereco($endereco) { $this->endereco = $endereco; }

    public function getObservacoes() { return $this->observacoes; }
    public function setObservacoes($observacoes) { $this->observacoes = $observacoes; }

    public function getTags() { return $this->tags; }
    public function setTags($tags) { $this->tags = $tags; }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; }
}
