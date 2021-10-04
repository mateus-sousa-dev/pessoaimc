<?php

namespace App\Interfaces;

use Illuminate\Foundation\Bus\PendingDispatch;

interface Mensageiro
{
    public function enviar(): PendingDispatch;
    public function defineMensagem(string $mensagem): void;
    public function mensagem(): string;
}
