<?php

namespace App\Enums;

enum RequestLocations: string
{
    public function getByCode(string $key): string
    {
        return match ($key) {
            'br' => 'pt-BR',
            'en' => 'en-US',
            'uk' => 'en-UK',
            'es' => 'es-ES',
            'fr' => 'fr-FR',
            default => 'pt-BR',
        };
    }
}
