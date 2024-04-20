<?php

declare(strict_types=1);

use VPremiss\Arabicable\Exceptions\ArabicableFunctionExistsException;

if (function_exists('ar_indian')) {
    throw new ArabicableFunctionExistsException('The arabic function "ar_indian()" already exists!');
} else {
    function ar_indian(string $property): string
    {
        return $property . config('arabicable.property_suffix_keys.numbers_to_indian');
    }
}

if (function_exists('ar_with_harakat')) {
    throw new ArabicableFunctionExistsException('The arabic function "ar_with_harakat()" already exists!');
} else {
    function ar_with_harakat(string $property): string
    {
        return $property . config('arabicable.property_suffix_keys.text_with_harakat');
    }
}

if (function_exists('ar_searchable')) {
    throw new ArabicableFunctionExistsException('The arabic function "ar_searchable()" already exists!');
} else {
    function ar_searchable(string $property): string
    {
        return $property . config('arabicable.property_suffix_keys.text_for_search');
    }
}
