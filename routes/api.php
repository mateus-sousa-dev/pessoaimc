<?php

use App\Http\Controllers\PessoaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/pessoas', PessoaController::class);
