<?php

namespace App\Interfaces;

use Illuminate\Foundation\Bus\PendingDispatch;

interface Mensageiro
{
    public function enviar(): PendingDispatch;

    public function defineMensagem($mensagem);
}
