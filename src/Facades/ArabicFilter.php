<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string withHarakat(string $text)
 * @method static string withoutHarakat(string $text)
 * @method static string forSearch(string $text)
 *
 * @see \VPremiss\Arabicable\ArabicFilter
 */
class ArabicFilter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Arabicable\ArabicFilter::class;
    }
}
