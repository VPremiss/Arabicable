<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use VPremiss\Arabicable\Facades\Arabic;

class ArabicFilter
{
    public function withHarakat(string $text): string
    {
        Arabic::validateForTextSpacing($text);

        $text = Arabic::convertNumeralsToIndian($text);
        $text = Arabic::normalizeSpaces($text);
        $text = Arabic::convertPunctuationMarksToArabic($text);
        $text = Arabic::removeSpacesAroundPunctuationMarks($text);

        if (!config('arabicable.spacing_after_punctuation_only')) {
            $text = Arabic::addSpacesBeforePunctuationMarks($text);
        }

        $text = Arabic::addSpacesAfterPunctuationMarks($text);
        $text = Arabic::removeSpacesWithinEnclosingMarks($text);
        $text = Arabic::refineSpacesBetweenPunctuationMarks($text);

        return $text;
    }

    public function withoutHarakat(string $text): string
    {
        $text = $this->withHarakat($text);
        $text = Arabic::removeHarakat($text);

        return $text;
    }

    public function forSearch(string $text): string
    {
        $text = Arabic::removeHarakat($text);
        $text = Arabic::removeAllPunctuationMarks($text);
        $text = Arabic::convertNumeralsToArabicAndIndianSequences($text);
        $text = Arabic::deduplicateArabicAndIndianNumeralSequences($text);
        $text = Arabic::normalizeHuroof($text);
        $text = Arabic::normalizeSpaces($text);

        return $text;
    }
}
