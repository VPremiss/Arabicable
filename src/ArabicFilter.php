<?php

namespace VPremiss\Arabicable;

use VPremiss\Arabicable\Facades\Arabic;

class ArabicFilter
{
    public function forSearch(string $text): string
    {
        $text = Arabic::removeHarakat($text);
        $text = Arabic::removePunctuationMarks($text);
        $text = Arabic::convertNumeralsToArabicAndIndianSequences($text);
        $text = Arabic::deduplicateArabicAndIndianNumeralSequences($text);
        $text = Arabic::normalizeHuroof($text);
        $text = Arabic::removeEnclosingMarks($text);
        $text = Arabic::removeEmptySpaces($text);

        return $text;
    }

    public function withHarakat(string $text): string
    {
        $text = Arabic::convertNumeralsToIndian($text);
        $text = Arabic::convertToFasila($text);
        $text = Arabic::removeSpacesWithinQuotes($text);
        $text = Arabic::addSpacesAroundPunctuationMarks($text);
        $text = Arabic::addSpacesForColonsAfterDoubleQuotes($text);

        return $text;
    }

    public function withoutHarakat(string $text): string
    {
        $text = Arabic::convertNumeralsToIndian($text);
        $text = Arabic::removeHarakat($text);
        $text = Arabic::convertToFasila($text);
        $text = Arabic::removeSpacesWithinQuotes($text);
        $text = Arabic::addSpacesAroundPunctuationMarks($text);
        $text = Arabic::addSpacesForColonsAfterDoubleQuotes($text);

        return $text;
    }
}
