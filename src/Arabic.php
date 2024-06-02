<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use VPremiss\Arabicable\Enums\ArabicLinguisticConcept;
use VPremiss\Arabicable\Enums\ArabicSpecialCharacters;
use VPremiss\Arabicable\Enums\CommonArabicTextType;
use VPremiss\Arabicable\Facades\ArabicFilter;
use VPremiss\Arabicable\Models\ArabicPlural;
use VPremiss\Arabicable\Models\CommonArabicText;
use VPremiss\Arabicable\Support\Concerns;
use VPremiss\Crafty\Facades\CraftyPackage;

// TODO consider some python pyarabic package methods and consts maybe?
class Arabic
{
    use Concerns\HandlesNumerals;
    use Concerns\HandlesPunctuation;
    use Concerns\HandlesSpaces;

    public function removeHarakat(string $text): string
    {
        return strtr(
            $text,
            array_fill_keys(
                ArabicSpecialCharacters::Harakat->get(),
                '',
            ),
        );
    }

    public function normalizeHuroof(string $text): string
    {
        $huroof = ['أ', 'ى', 'إ', 'ٕ ', 'ﭐ', 'ﭑ'];
        $text = strtr($text, array_fill_keys($huroof, 'ا'));
        $huroof = ['ﭒ', 'ﭓ', 'ﭔ', 'ٕﭕ', 'ﭖ'];
        $text = strtr($text, array_fill_keys($huroof, 'ب'));
        $huroof = ['ئ', 'ؤ', 'آ'];
        $text = strtr($text, array_fill_keys($huroof, 'ء'));

        return $text;
    }

    public function getSingulars(string|array $plurals, bool $uniqueFiltered = true): array
    {
        // ? Ensure $plurals is an array
        $plurals = Arr::wrap($plurals);

        // ? Cache all plurals as they're not updated much often
        $cacheKey = CraftyPackage::getConfiguration('arabicable.arabic_plural.cache_key');
        $allPlurals = Cache::rememberForever($cacheKey, fn () => ArabicPlural::query()->get());

        // ? Filter plurals for search first
        $filteredPlurals = collect($plurals)
            ->map(fn ($plural) => ArabicFilter::forSearch($plural))
            ->toArray();

        // ? Get the singulars only now
        $singulars = $allPlurals
            ->whereIn(ar_searchable('plural'), $filteredPlurals)
            ->pluck('singular');

        return $uniqueFiltered ? $singulars->unique()->toArray() : $singulars->toArray();
    }

    public function getPlurals(string|array $singulars, bool $uniqueFiltered = true): array
    {
        // ? Ensure $singulars is an array
        $singulars = Arr::wrap($singulars);

        // ? Cache all plurals as they're not updated much often
        $cacheKey = CraftyPackage::getConfiguration('arabicable.arabic_plural.cache_key');
        $allPlurals = Cache::rememberForever($cacheKey, fn () => ArabicPlural::query()->get());

        // ? Filter singulars for search first
        $filteredSingulars = collect($singulars)
            ->map(fn ($singular) => ArabicFilter::forSearch($singular))
            ->toArray();

        // ? Get the plurals only now
        $plurals = $allPlurals
            ->whereIn(ar_searchable('singular'), $filteredSingulars)
            ->pluck('plural');

        return $uniqueFiltered ? $plurals->unique()->toArray() : $plurals->toArray();
    }

    public function removeCommons(string|array $words, array $excludedTypes = [], bool $asString = false): string|array
    {
        $baseCacheKey = CraftyPackage::getConfiguration('arabicable.common_arabic_text.cache_key');
        $types = CommonArabicTextType::cases();

        // ? Cache each type of common text separately and store in an associative array
        $cachedCommonTexts = [];
        foreach ($types as $type) {
            $typeKey = $baseCacheKey . '.' . $type->value;
            $cachedCommonTexts[$type->value] = Cache::rememberForever($typeKey, function () use ($type) {
                return CommonArabicText::where('type', $type->value)
                    ->get()
                    ->sortByDesc(fn ($model) => mb_strlen($model->{ar_searchable('content')}));
            });
        }

        // ? Ensure processing as a string
        $wordsSentence = is_string($words) ? $words : implode(' ', $words);

        // ? Filter using an array though
        $originalWordsArray = explode(' ', $wordsSentence);
        $filteredWordsArray = array_map(fn ($word) => ArabicFilter::forSearch($word), $originalWordsArray);

        // ? Process and remove common texts except the excluded types
        foreach ($cachedCommonTexts as $type => $texts) {
            if (in_array($type, $excludedTypes)) {
                continue;
            }

            foreach ($texts as $model) {
                $pattern = '/\b' . preg_quote($model->{ar_searchable('content')}, '/') . '\b/u';
                foreach ($filteredWordsArray as $index => $word) {
                    if (preg_match($pattern, $word)) {
                        $originalWordsArray[$index] = '|'; // ? Using a special splitting mark
                    }
                }
            }
        }

        // ? Normalize spaces, split into sentences, and get rid of singular left-over letters
        $wordsSentence = implode(' ', $originalWordsArray);
        $wordsSentence = self::normalizeSpaces($wordsSentence);
        $sentences = array_filter(preg_split('/\s*\|\s*/', $wordsSentence), fn ($sentence) => mb_strlen(trim($sentence)) > 1);

        if ($asString) {
            return implode(' ', $sentences);
        } else {
            return $sentences;
        }
    }

    public function clearConceptCache(ArabicLinguisticConcept $concept): void
    {
        $cacheType = $concept === ArabicLinguisticConcept::Plurals ? 'arabic_plural' : 'common_arabic_text';
        $baseCacheKey = CraftyPackage::getConfiguration("arabicable.$cacheType.cache_key");
        $types = CommonArabicTextType::cases();

        foreach ($types as $type) {
            $typeKey = $baseCacheKey . '.' . $type->value;
            Cache::forget($typeKey);
        }
    }
}
