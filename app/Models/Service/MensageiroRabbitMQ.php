<?php

namespace App\Models\Service;

use App\Interfaces\Mensageiro;
use App\Jobs\RabbitMQJob;
use Illuminate\Foundation\Bus\PendingDispatch;

class MensageiroRabbitMQ implements Mensageiro
{
    private $mensagem;

    public function __construct($mensagem = null)
    {
        $this->mensagem = $mensagem;
    }

    public function enviar(): PendingDispatch
    {
        return RabbitMQJob::dispatch($this->mensagem);
    }

    public function defineMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }
}
