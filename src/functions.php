<?php

declare(strict_types=1);

use VPremiss\Arabicable\Enums\ArabicSpecialCharacters;
use VPremiss\Arabicable\Support\Exceptions\ArabicableFunctionException;
use VPremiss\Crafty\Enums\DataType;
use VPremiss\Crafty\Facades\Crafty;
use VPremiss\Crafty\Facades\CraftyPackage;

if (function_exists('ar_indian')) {
    throw new ArabicableFunctionException('The arabic function "ar_indian()" already exists!');
} else {
    function ar_indian(string $property): string
    {
        return $property . CraftyPackage::getConfiguration('arabicable.property_suffix_keys.numbers_to_indian');
    }
}

if (function_exists('ar_with_harakat')) {
    throw new ArabicableFunctionException('The arabic function "ar_with_harakat()" already exists!');
} else {
    function ar_with_harakat(string $property): string
    {
        return $property . CraftyPackage::getConfiguration('arabicable.property_suffix_keys.text_with_harakat');
    }
}

if (function_exists('ar_searchable')) {
    throw new ArabicableFunctionException('The arabic function "ar_searchable()" already exists!');
} else {
    function ar_searchable(string $property): string
    {
        return $property . CraftyPackage::getConfiguration('arabicable.property_suffix_keys.text_for_search');
    }
}

if (function_exists('arabicable_special_characters')) {
    throw new ArabicableFunctionException('The arabic function "arabicable_special_characters()" already exists!');
} else {
    function arabicable_special_characters(
        array|ArabicSpecialCharacters $only = [],
        array|ArabicSpecialCharacters $except = [],
        bool $combineInstead = false,
    ): array {
        $characters = Crafty::filterProps(ArabicSpecialCharacters::cases(), $only, $except);

        if (!Crafty::validatedArray($characters, DataType::SpecificEnum(ArabicSpecialCharacters::class))) {
            throw new ArabicableFunctionException("Only ArabicSpecialCharacters enum cases are allowed.");
        }

        $characterArrays = collect($characters)->map(fn ($char) => $char->get())->toArray();

        if ($combineInstead) {
            if (($count = count($characterArrays)) != 2) {
                throw new ArabicableFunctionException(
                    "Combining works only with exactly two character sets. Currently $count are considered."
                );
            }

            return array_combine($characterArrays[0], $characterArrays[1]);
        } else {
            return array_merge(...$characterArrays);
        }
    }
}
