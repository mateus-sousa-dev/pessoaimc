<?php

namespace App\Models\Service;

use App\Interfaces\Mensageiro;
use App\Jobs\RabbitMQJob;
use Illuminate\Foundation\Bus\PendingDispatch;

class MensageiroRabbitMQ implements Mensageiro
{
    private $mensagem;

    public function enviar(): PendingDispatch
    {
        return RabbitMQJob::dispatch($this->mensagem);
    }

    public function defineMensagem(string $mensagem): void
    {
        $this->mensagem = $mensagem;
    }

    public function mensagem(): string
    {
        return $this->mensagem;
    }
}
