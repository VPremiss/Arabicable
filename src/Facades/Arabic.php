<?php

namespace VPremiss\Arabicable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VPremiss\Arabicable\Arabic
 */
class Arabic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Arabicable\Arabic::class;
    }
}
