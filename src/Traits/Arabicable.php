<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Traits;

use VPremiss\Arabicable\Observers\ModelArabicObserver;

trait Arabicable
{
    public static function bootArabicable()
    {
        static::observe(new ModelArabicObserver);
    }
}
