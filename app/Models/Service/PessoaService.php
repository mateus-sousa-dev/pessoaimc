<?php

namespace App\Models\Service;

use App\Interfaces\Mensageiro;
use App\Models\Entity\Pessoa;
use App\Models\Repository\PessoaRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorErrors;
use Illuminate\Database\Eloquent\Collection;

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

    public function listarPessoas(): Collection
    {
        return $this->repository->listarPessoas();
    }

    public function cadastrarPessoa(array $arrDados): Pessoa
    {
        $arrDados['imc'] = $this->calculadoraImc->calcular($arrDados['altura'], $arrDados['peso']);

        $pessoa = $this->repository->cadastrarPessoa($arrDados);

        $this->mensageiro->defineMensagem("Cadastro de pessoa {$pessoa->nome}");
        $this->mensageiro->enviar();

        return $pessoa;
    }

    public function atualizarPessoa(string $id, array $arrDados): Pessoa
    {
        $arrDados['imc'] = $this->calculadoraImc->calcular($arrDados['altura'], $arrDados['peso']);

        $pessoa = $this->repository->atualizarPessoa($id, $arrDados);

        $this->mensageiro->defineMensagem("Edição da pessoa {$pessoa->nome}");
        $this->mensageiro->enviar();

        return $pessoa;
    }

    public function buscarPessoaPorId(string $id): Pessoa
    {
        return $this->repository->buscarPessoaPorId($id);
    }

    public function deletarPessoa(string $id): Pessoa
    {
        $pessoaExcluida = $this->repository->deletarPessoa($id);
        $this->mensageiro->defineMensagem("Exclusão da pessoa {$pessoaExcluida->nome}");
        $this->mensageiro->enviar();

        return $pessoaExcluida;
    }

    private function mapeiaErrosValidator(ValidatorErrors $validator): ?array
    {
        foreach ($validator->errors()->messages() as $messageError) {
            $msgErro[] = $messageError[array_key_first($messageError)];
        }
        return $msgErro;
    }
}
