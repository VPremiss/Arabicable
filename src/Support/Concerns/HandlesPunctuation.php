<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Support\Concerns;

use VPremiss\Crafty\Facades\CraftyPackage;

trait HandlesPunctuation
{
    public const PUNCTUATION_MARKS = ['.', '!', ':', '-'];
    public const FOREIGN_PUNCTUATION_MARKS = [',', ';', '?'];
    public const ARABIC_PUNCTUATION_MARKS = ['،', '؛', '؟'];

    public const ENCLOSING_MARKS = ["'", '"'];
    public const ENCLOSING_STARTER_MARKS = ['(', '{', '[', '<', '<<',];
    public const ENCLOSING_ENDER_MARKS = [')', '}', ']', '>', '>>'];
    public const ARABIC_ENCLOSING_MARKS = ['/'];
    public const ARABIC_ENCLOSING_STARTER_MARKS = ['﴾', '⦗', '«'];
    public const ARABIC_ENCLOSING_ENDER_MARKS = ['﴿', '⦘', '»'];

    protected function getAllPunctuationMarks(): array
    {
        return array_merge(
            static::PUNCTUATION_MARKS,
            static::FOREIGN_PUNCTUATION_MARKS,
            static::ARABIC_PUNCTUATION_MARKS,
            static::ENCLOSING_MARKS,
            static::ENCLOSING_STARTER_MARKS,
            static::ENCLOSING_ENDER_MARKS,
            static::ARABIC_ENCLOSING_MARKS,
            static::ARABIC_ENCLOSING_STARTER_MARKS,
            static::ARABIC_ENCLOSING_ENDER_MARKS,
        );
    }

    protected function getAllEnclosingMarks(): array
    {
        return array_merge(
            static::ENCLOSING_MARKS,
            static::ENCLOSING_STARTER_MARKS,
            static::ENCLOSING_ENDER_MARKS,
            static::ARABIC_ENCLOSING_MARKS,
            static::ARABIC_ENCLOSING_STARTER_MARKS,
            static::ARABIC_ENCLOSING_ENDER_MARKS,
        );
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
        $text = str_replace(static::FOREIGN_PUNCTUATION_MARKS, static::ARABIC_PUNCTUATION_MARKS, $text);

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
