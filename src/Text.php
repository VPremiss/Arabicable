<?php

namespace VPremiss\Arabicable;

class Text
{
    use Concerns\HandlesNumerals;
    use Concerns\HandlesSpaces;

    public const PUNCTUATION_MARKS = ['.', '!', ':', ',', '-'];

    public const ENCLOSING_MARKS = ['\'', '"', '⦘', '⦗', '(', ')', '»', '«', '{', '}', '[', ']'];

    public function removePunctuationMarks(string $text): string
    {
        return strtr($text, array_fill_keys(static::PUNCTUATION_MARKS, ''));
    }

    public function removeEnclosingMarks(string $text): string
    {
        return strtr($text, array_fill_keys(static::ENCLOSING_MARKS, ''));
    }
}
