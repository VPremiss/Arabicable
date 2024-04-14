<?php

namespace VPremiss\Arabicable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string removePunctuationMarks(string $text)
 * @method static string removeEnclosingMarks(string $text)
 * @method static string convertNumeralsToIndian(string $text)
 * @method static string convertNumeralsToArabicAndIndianSequences(string $text)
 * @method static string deduplicateArabicAndIndianNumeralSequences(string $text)
 * @method static string addSpacesAroundPunctuationMarks(string $text)
 * @method static string addSpacesForColonsAfterDoubleQuotes(string $text)
 * @method static string removeEmptySpaces(string $text)
 * @method static string removeSpacesWithinQuotes(string $text)
 * 
 * @see \VPremiss\Arabicable\Text
 */
class Text extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Arabicable\Text::class;
    }
}
