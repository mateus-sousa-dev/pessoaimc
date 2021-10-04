<?php


namespace App\Models\Service;


class CalculadoraImc
{
    public function calcular($altura, $peso)
    {
        return round($peso / ($altura ** 2), 2);
    }
}
