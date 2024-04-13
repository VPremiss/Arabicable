<?php

namespace VPremiss\Arabicable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VPremiss\Arabicable\ArabicFilter
 */
class ArabicFilter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Arabicable\ArabicFilter::class;
    }
}
