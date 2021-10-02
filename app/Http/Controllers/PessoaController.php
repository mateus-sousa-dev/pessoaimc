<?php

namespace App\Http\Controllers;

use App\Http\Requests\PessoaFormRequest;
use App\Models\Service\{CalculadoraImc, MensageiroRabbitMQ, PessoaService, RetornoDeErros};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PessoaController
{
    private $service;

    public function __construct()
    {
        $this->service = new PessoaService(
            new MensageiroRabbitMQ(),
            new CalculadoraImc()
        );
    }

    public function index(): JsonResponse
    {
        try {
            $pessoas = $this->service->listarPessoas();
            return response()->json($pessoas, 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível listar as pessoas.");
        }
    }

    public function store(PessoaFormRequest $request): JsonResponse
    {
        try {
            $pessoa = $this->service->cadastrarPessoa($request->all());
            return response()->json("Cadastro da pessoa {$pessoa->nome}", 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível cadastrar pessoa.");
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $pessoa = $this->service->buscarPessoaPorId($id);
            return response()->json($pessoa, 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível buscar pessoa por ID.");
        }
    }

    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $pessoa = $this->service->atualizarPessoa($id, $request->all());
            return response()->json("Edição da pessoa {$pessoa->nome}", 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível atualizar pessoa.");
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $pessoaExcluida = $this->service->deletarPessoa($id);
            return response()->json("Exclusão da pessoa {$pessoaExcluida->nome}", 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível excluir pessoa.");
        }
    }
}
