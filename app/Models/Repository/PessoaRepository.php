<?php

namespace App\Models\Repository;

use App\Models\Entity\Pessoa;

class PessoaRepository
{
    private $pessoa;

    public function __construct()
    {
        $this->pessoa = new Pessoa();
    }

    public function listarPessoas()
    {
        return $this->pessoa->get();
    }

    public function cadastrarPessoa($arrDados)
    {
        $this->persistePessoa($arrDados);
        return $this->pessoa;
    }

    public function buscarPessoaPorId($id)
    {
        return $this->pessoa->find($id);
    }

    public function atualizarPessoa($id, $arrDados)
    {
        $this->pessoa = $this->pessoa->findOrFail($id);
        $this->persistePessoa($arrDados);
        return $this->pessoa;
    }

    public function persistePessoa($arrDados)
    {
        $this->pessoa->nome = $arrDados['nome'];
        $this->pessoa->sexo = $arrDados['sexo'];
        $this->pessoa->peso = $arrDados['peso'];
        $this->pessoa->altura = $arrDados['altura'];
        $this->pessoa->imc = $arrDados['imc'];
        $this->pessoa->save();
    }

    public function deletarPessoa($id)
    {
        $this->pessoa = $this->pessoa->findOrFail($id);

        $pessoa = clone $this->pessoa;
        $this->pessoa->delete();
        return $pessoa;
    }

    public function verificarExistenciaDePessoaPorNome($nome)
    {
        return $this->pessoa->where('nome', $nome)->exists();
    }
}
