<?php

namespace App\Http\Controllers;

use App\Models\Service\CalculadoraImc;
use App\Models\Service\MensageiroRabbitMQ;
use App\Models\Service\PessoaService;
use App\Models\Service\RetornoDeErros;
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

    public function index()
    {
        try {
            $pessoas = $this->service->listarPessoas();
            return response()->json($pessoas, 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível listar as pessoas.");
        }
    }

    public function store(Request $request)
    {
        try {
            $msgErro = $this->service->validarDadosCadastro($request->all());
            if ($msgErro) {
                return response()->json($msgErro, 200);
            }
            $pessoa = $this->service->cadastrarPessoa($request->all());
            return response()->json("Cadastro da pessoa {$pessoa->nome}", 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível cadastrar pessoa.");
        }
    }

    public function show($id)
    {
        try {
            $pessoa = $this->service->buscarPessoaPorId($id);
            return response()->json($pessoa, 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível buscar pessoa por ID.");
        }
    }

    public function update($id, Request $request)
    {
        try {
            $msgErro = $this->service->validarDadosEdicao($request->all());
            if ($msgErro) {
                return response()->json($msgErro, 200);
            }

            $pessoa = $this->service->atualizarPessoa($id, $request->all());
            return response()->json("Edição da pessoa {$pessoa->nome}", 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível atualizar pessoa.");
        }
    }

    public function destroy($id)
    {
        try {
            $pessoaExcluida = $this->service->deletarPessoa($id);
            return response()->json("Exclusão da pessoa {$pessoaExcluida->nome}", 200);
        } catch (\Exception $e) {
            return RetornoDeErros::lidarComErro($e, "Não foi possível excluir pessoa.");
        }
    }
}
