<?php

namespace App\Models\Service;

use Illuminate\Support\Facades\Log;

class RetornoDeErros
{
    public static function lidarComErro(\Exception $e, $msgErro)
    {
        Log::error($e);
        return response()->json($msgErro, 500);
    }
}
