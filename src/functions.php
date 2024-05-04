<?php

declare(strict_types=1);

use VPremiss\Arabicable\Support\Exceptions\ArabicableFunctionDoesNotExistException;
use VPremiss\Crafty\Facades\CraftyPackage;

if (function_exists('ar_indian')) {
    throw new ArabicableFunctionDoesNotExistException('The arabic function "ar_indian()" already exists!');
} else {
    function ar_indian(string $property): string
    {
        return $property . CraftyPackage::getConfiguration('arabicable.property_suffix_keys.numbers_to_indian');
    }
}

if (function_exists('ar_with_harakat')) {
    throw new ArabicableFunctionDoesNotExistException('The arabic function "ar_with_harakat()" already exists!');
} else {
    function ar_with_harakat(string $property): string
    {
        return $property . CraftyPackage::getConfiguration('arabicable.property_suffix_keys.text_with_harakat');
    }
}

if (function_exists('ar_searchable')) {
    throw new ArabicableFunctionDoesNotExistException('The arabic function "ar_searchable()" already exists!');
} else {
    function ar_searchable(string $property): string
    {
        return $property . CraftyPackage::getConfiguration('arabicable.property_suffix_keys.text_for_search');
    }
}
