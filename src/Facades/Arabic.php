<?php

namespace VPremiss\Arabicable\Facades;

use Illuminate\Support\Facades\Facade;

// TODO check if adding the other Text method docs is needed

/**
 * @method static string removeHarakat(string $text)
 * @method static string normalizeHuroof(string $text)
 * @method static string convertToFasila(string $text)
 * 
 * @see \VPremiss\Arabicable\Arabic
 */
class Arabic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Arabicable\Arabic::class;
    }
}
