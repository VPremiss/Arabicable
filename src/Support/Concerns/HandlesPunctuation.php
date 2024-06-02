<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Support\Concerns;

use VPremiss\Arabicable\Enums\ArabicSpecialCharacters;
use VPremiss\Crafty\Facades\CraftyPackage;

trait HandlesPunctuation
{
    protected function getAllPunctuationMarks(): array
    {
        return arabicable_special_characters(only: [
            ArabicSpecialCharacters::PunctuationMarks,
            ArabicSpecialCharacters::ForeignPunctuationMarks,
            ArabicSpecialCharacters::ArabicPunctuationMarks,
            ArabicSpecialCharacters::EnclosingMarks,
            ArabicSpecialCharacters::EnclosingStarterMarks,
            ArabicSpecialCharacters::EnclosingEnderMarks,
            ArabicSpecialCharacters::ArabicEnclosingMarks,
            ArabicSpecialCharacters::ArabicEnclosingStarterMarks,
            ArabicSpecialCharacters::ArabicEnclosingEnderMarks,
        ]);
    }

    protected function getAllEnclosingMarks(): array
    {
        return arabicable_special_characters(only: [
            ArabicSpecialCharacters::EnclosingMarks,
            ArabicSpecialCharacters::EnclosingStarterMarks,
            ArabicSpecialCharacters::EnclosingEnderMarks,
            ArabicSpecialCharacters::ArabicEnclosingMarks,
            ArabicSpecialCharacters::ArabicEnclosingStarterMarks,
            ArabicSpecialCharacters::ArabicEnclosingEnderMarks,
        ]);
    }

    protected function getFilteredPunctuationMarks(array $inclusions = [], array $exclusions = []): array
    {
        $marks = array_merge(
            $this->getAllPunctuationMarks(),
            $inclusions,
        );

        return array_diff($marks, $exclusions);
    }

    public function convertPunctuationMarksToArabic(string $text): string
    {
        $text = str_replace(
            ArabicSpecialCharacters::ForeignPunctuationMarks->get(),
            ArabicSpecialCharacters::ArabicPunctuationMarks->get(),
            $text,
        );

        if ($normalizedMarks = CraftyPackage::getConfiguration('arabicable.normalized_punctuation_marks')) {
            foreach ($normalizedMarks as $mark => $fromOthers) {
                $text = str_replace($fromOthers, $mark, $text);
            }
        }

        return $text;
    }

    public function removeAllPunctuationMarks(string $text): string
    {
        return strtr($text, array_fill_keys($this->getAllPunctuationMarks(), ''));
    }
}
