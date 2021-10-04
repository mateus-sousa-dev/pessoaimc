<?php

namespace Tests\Unit;

use App\Models\Service\CalculadoraImc;
use App\Models\Service\MensageiroRabbitMQ;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function testeImcEstaSendoCalculadoCorretamente()
    {
        $calculadora = new CalculadoraImc();
        $imc = $calculadora->calcular('1.80', '80');

        self::assertEquals('24.69', $imc);
    }

    /**
     * @dataProvider mensageiro
     */
    public function testMensagemDefinidaNoMensageiroEstaCorreta(MensageiroRabbitMQ $mensageiroRabbitMQ)
    {
        self::assertEquals('Uma mensagem de Teste', $mensageiroRabbitMQ->mensagem());
    }

    public function mensageiro()
    {
        $mensageiro = $this->createMock(MensageiroRabbitMQ::class);
        $mensageiro->method('mensagem')->willReturn('Uma mensagem de Teste');

        return [
            'mensageiro' => [$mensageiro]
        ];
    }
}
