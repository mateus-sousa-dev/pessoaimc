<?php


namespace App\Models\Service;


class CalculadoraImc
{
    public function calcular($altura, $peso)
    {
        return $peso / ($altura ** 2);
    }
}
