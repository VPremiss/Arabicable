<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Support\Concerns;

use VPremiss\Arabicable\Enums\ArabicSpecialCharacters;

trait HandlesNumerals
{
    public function convertNumeralsToIndian(string $text): string
    {
        return strtr(
            $text,
            array_combine(
                ArabicSpecialCharacters::IndianNumerals->get(),
                ArabicSpecialCharacters::ArabicNumerals->get(),
            ),
        );
    }

    public function convertNumeralsToArabicAndIndianSequences(string $text): string
    {
        $originalTextHasArabicNumerals = preg_match(
            '/[' . implode('', ArabicSpecialCharacters::ArabicNumerals->get()) . ']/u',
            $text,
        );
        $originalTextHasIndianNumerals = preg_match(
            '/[' . implode('', ArabicSpecialCharacters::IndianNumerals->get()) . ']/',
            $text,
        );

        if ($originalTextHasArabicNumerals) {
            $text = preg_replace_callback(
                '/[' . implode('', ArabicSpecialCharacters::ArabicNumerals->get()) . ']+/u',
                function ($matches) {
                    $arabicNumber = $matches[0];
                    $indianNumber = str_replace(
                        ArabicSpecialCharacters::ArabicNumerals->get(),
                        ArabicSpecialCharacters::IndianNumerals->get(),
                        $arabicNumber,
                    );

                    return $arabicNumber . ' ' . $indianNumber;
                },
                $text,
            );
        }

        if ($originalTextHasIndianNumerals) {
            $text = preg_replace_callback(
                '/[' . implode('', ArabicSpecialCharacters::IndianNumerals->get()) . ']+/',
                function ($matches) {
                    $indianNumber = $matches[0];
                    $arabicNumber = str_replace(
                        ArabicSpecialCharacters::IndianNumerals->get(),
                        ArabicSpecialCharacters::ArabicNumerals->get(),
                        $indianNumber,
                    );

                    return $indianNumber . ' ' . $arabicNumber;
                },
                $text,
            );
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
