<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string removeHarakat(string $text)
 * @method static string normalizeHuroof(string $text)
 * @method static array getSingulars(string|array $plurals, bool $uniqueFiltered = true)
 * @method static array getPlurals(string|array $singulars, bool $uniqueFiltered = true)
 * @method static string|array removeCommons(string|array $words, array $excludedTypes = [], bool $asString = false)
 * @method static void clearConceptCache(ArabicLinguisticConcept $concept)
 * @method static string convertNumeralsToIndian(string $text)
 * @method static string convertNumeralsToArabicAndIndianSequences(string $text)
 * @method static string deduplicateArabicAndIndianNumeralSequences(string $text)
 * @method static string convertPunctuationMarksToArabic(string $text)
 * @method static string removeAllPunctuationMarks(string $text)
 * @method static void validateForTextSpacing(string $text)
 * @method static string normalizeSpaces(string $text)
 * @method static string addSpacesBeforePunctuationMarks(string $text, array $inclusions = [], array $exclusions = [])
 * @method static string addSpacesAfterPunctuationMarks(string $text, array $inclusions = [], array $exclusions = [])
 * @method static string removeSpacesAroundPunctuationMarks(string $text, array $inclusions = [], array $exclusions = [])
 * @method static string removeSpacesWithinEnclosingMarks(string $text, array $exclusions = [])
 * @method static string refineSpacesBetweenPunctuationMarks(string $text)
 *
 * @see \VPremiss\Arabicable\Arabic
 */
class Arabic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VPremiss\Arabicable\Arabic::class;
    }
}
