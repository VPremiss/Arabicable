<?php

use VPremiss\Arabicable\Exceptions\ArabicableConfigurationException;
use VPremiss\Arabicable\Exceptions\ArabicableFunctionExistsException;

if (function_exists('ar_indian')) {
    throw new ArabicableFunctionExistsException('The arabic function "ar_indian()" already exists!');
} else {
    function ar_indian(string $property): string
    {
        if (empty($indianColumnAffix = config('arabicable.property_suffix_keys.numbers_to_indian'))) {
            throw new ArabicableConfigurationException('The configuration suffix key for Indian numerals is empty!');
        }

        return $property . $indianColumnAffix;
    }
}

if (function_exists('ar_with_harakat')) {
    throw new ArabicableFunctionExistsException('The arabic function "ar_with_harakat()" already exists!');
} else {
    function ar_with_harakat(string $property): string
    {
        if (empty($withHarakatColumnAffix = config('arabicable.property_suffix_keys.text_with_harakat'))) {
            throw new ArabicableConfigurationException('The configuration suffix key for Arabic with harakat text is empty!');
        }

        return $property . $withHarakatColumnAffix;
    }
}

if (function_exists('ar_searchable')) {
    throw new ArabicableFunctionExistsException('The arabic function "ar_searchable()" already exists!');
} else {
    function ar_searchable(string $property): string
    {
        if (empty($searchableColumnAffix = config('arabicable.property_suffix_keys.text_for_search'))) {
            throw new ArabicableConfigurationException('The configuration suffix key for Arabic searchable text is empty!');
        }

        return $property . $searchableColumnAffix;
    }
}
