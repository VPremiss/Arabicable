<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use Illuminate\Support\Facades\Cache;
use VPremiss\Arabicable\Models\CommonArabicText;
use VPremiss\Arabicable\Support\Concerns;
use VPremiss\Crafty\Facades\CraftyPackage;

// TODO consider some python pyarabic package methods and consts maybe?
class Arabic
{
    use Concerns\HandlesNumerals;
    use Concerns\HandlesPunctuation;
    use Concerns\HandlesSpaces;

    public const HARAKAT = ['ِ', 'ُ', 'ٓ', 'ٰ', 'ْ', 'ٌ', 'ٍ', 'ً', 'ّ', 'َ', 'ـ', 'ٗ'];

    public function removeHarakat(string $text): string
    {
        return strtr($text, array_fill_keys(static::HARAKAT, ''));
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

    public function removeCommons(string|array $words): string|array
    {
        $cacheKey = CraftyPackage::getConfiguration('arabicable.common_arabic_text.cache_key');

        // * Get commons sorted from the longest
        $commonTexts = Cache::rememberForever($cacheKey, function () {
            return CommonArabicText::all()->sortByDesc(fn ($model) => mb_strlen($model->content));
        });

        // ? Ensure processing as a string, not an array
        $wordsSentence = is_string($words) ? $words : implode(' ', $words);

        // * Remove commons, ensure only whole words are matched using regex with word boundaries
        foreach ($commonTexts as $model) {
            $pattern = '/\b' . preg_quote($model->content, '/') . '\b/u';
            $wordsSentence = preg_replace($pattern, '', $wordsSentence);
        }

        // * Filter out any leftover single letters and stuff
        $wordsSentence = implode(' ', array_filter(explode(' ', $wordsSentence), fn ($word) => mb_strlen($word) > 1));
        $wordsSentence = self::normalizeSpaces($wordsSentence);

        // * Return in the same form it was input
        $words = is_string($words) ? $wordsSentence : explode(' ', $wordsSentence);

        return $words;
    }
}
