<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Support\Concerns;

trait HandlesNumerals
{
    public const INDIAN_NUMERALS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    public const ARABIC_NUMERALS = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    public function convertNumeralsToIndian(string $text): string
    {
        return strtr($text, array_combine(static::INDIAN_NUMERALS, static::ARABIC_NUMERALS));
    }

    public function convertNumeralsToArabicAndIndianSequences(string $text): string
    {
        $originalTextHasArabicNumerals = preg_match('/[' . implode('', static::ARABIC_NUMERALS) . ']/u', $text);
        $originalTextHasIndianNumerals = preg_match('/[' . implode('', static::INDIAN_NUMERALS) . ']/', $text);

        if ($originalTextHasArabicNumerals) {
            $text = preg_replace_callback('/[' . implode('', static::ARABIC_NUMERALS) . ']+/u', function ($matches) {
                $arabicNumber = $matches[0];
                $indianNumber = str_replace(static::ARABIC_NUMERALS, static::INDIAN_NUMERALS, $arabicNumber);

                return $arabicNumber . ' ' . $indianNumber;
            }, $text);
        }

        if ($originalTextHasIndianNumerals) {
            $text = preg_replace_callback('/[' . implode('', static::INDIAN_NUMERALS) . ']+/', function ($matches) {
                $indianNumber = $matches[0];
                $arabicNumber = str_replace(static::INDIAN_NUMERALS, static::ARABIC_NUMERALS, $indianNumber);

                return $indianNumber . ' ' . $arabicNumber;
            }, $text);
        }

        return $text;
    }

    public function deduplicateArabicAndIndianNumeralSequences(string $text): string
    {
        $pattern = "/\d+|[\x{0660}-\x{0669}]+/u";

        preg_match_all($pattern, $text, $matches);

        if (!empty($matches)) {
            $numbers = implode(' ', $matches[0]);

            $uniqueNumbers = implode(' ', array_unique(explode(' ', $numbers)));

            $text = preg_replace($pattern, '', $text);
            $text = trim($text) . ' ' . $uniqueNumbers;
        }

        return $text;
    }
}
