<?php

namespace VPremiss\Arabicable\Concerns;

trait HandlesSpaces
{
    public function addSpacesAroundPunctuationMarks(string $text): string
    {
        return preg_replace('/(\s*)([' . implode('', static::PUNCTUATION_MARKS) . '])/u', ' $2', $text);
    }

    public function addSpacesForColonsAfterDoubleQuotes(string $text): string
    {
        return str_replace(':"', ': "', $text);
    }

    public function removeEmptySpaces(string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    public function removeSpacesWithinQuotes(string $text): string
    {
        $pattern = '/\s*"\s*(.*?)\s*"\s*/u';

        return preg_replace_callback($pattern, function ($matches) {
            return '"' . trim($matches[1]) . '"';
        }, $text);
    }
}
