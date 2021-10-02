<?php

namespace App\Models\Service;

use App\Interfaces\Mensageiro;
use App\Models\Repository\PessoaRepository;
use Illuminate\Support\Facades\Validator;

class PessoaService
{
    private $repository;
    private $mensageiro;
    private $calculadoraImc;

    public function __construct(Mensageiro $mensageiro, CalculadoraImc $calculadoraImc)
    {
        $this->repository = new PessoaRepository();
        $this->mensageiro = $mensageiro;
        $this->calculadoraImc = $calculadoraImc;
    }

    public function listarPessoas()
    {
        return $this->repository->listarPessoas();
    }

    public function cadastrarPessoa($arrDados)
    {
        $arrDados['imc'] = $this->calculadoraImc->calcular($arrDados['altura'], $arrDados['peso']);

        $pessoa = $this->repository->cadastrarPessoa($arrDados);

        $this->mensageiro->defineMensagem("Cadastro de pessoa {$pessoa->nome}");
        $this->mensageiro->enviar();

        return $pessoa;
    }

    public function atualizarPessoa($id, $arrDados)
    {
        $arrDados['imc'] = $this->calculadoraImc->calcular($arrDados['altura'], $arrDados['peso']);

        $pessoa = $this->repository->atualizarPessoa($id, $arrDados);

        $this->mensageiro->defineMensagem("Edição da pessoa {$pessoa->nome}");
        $this->mensageiro->enviar();

        return $pessoa;
    }

    public function validarDadosCadastro($arrDados)
    {
        $msgErro = $this->validarDadosForm($arrDados);

        if ($this->repository->verificarExistenciaDePessoaPorNome($arrDados['nome'])) {
            $msgErro[] = "Nome de pessoa já cadastrado.";
        }

        return $msgErro;
    }

    public function validarDadosEdicao($arrDados)
    {
        return $this->validarDadosForm($arrDados);
    }

    private function validarDadosForm($arrDados)
    {
        $validator = Validator::make($arrDados,[
            'nome' => ['required', 'max:150'],
            'sexo' => ['required', 'max:1'],
            'peso' => ['required', 'max:3'],
            'altura' => ['required', 'max:4']
        ],
        [
            'nome.unique' => "Nome ja existe"
        ]);
        $msgErro = [];
        if ($validator->fails()) {
            $msgErro = $this->mapeiaErrosValidator($validator);
        }

        return $msgErro;
    }

    public function buscarPessoaPorId($id)
    {
        return $this->repository->buscarPessoaPorId($id);
    }

    public function deletarPessoa($id)
    {
        $pessoaExcluida = $this->repository->deletarPessoa($id);
        $this->mensageiro->defineMensagem("Exclusão da pessoa {$pessoaExcluida->nome}");
        $this->mensageiro->enviar();

        return $pessoaExcluida;
    }

    private function mapeiaErrosValidator($validator)
    {
        foreach ($validator->errors()->messages() as $messageError) {
            $msgErro[] = $messageError[array_key_first($messageError)];
        }
        return $msgErro;
    }
}
