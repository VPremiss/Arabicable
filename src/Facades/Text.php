<?php

namespace VPremiss\Arabicable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VPremiss\Arabicable\Text
 */
class Text extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Arabicable\Text::class;
    }
}
