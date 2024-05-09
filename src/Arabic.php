<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use Illuminate\Support\Facades\Cache;
use VPremiss\Arabicable\Enums\CommonArabicTextType;
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
                    ->sortByDesc(fn ($model) => mb_strlen($model->content));
            });
        }

        // ? Ensure processing as a string
        $wordsSentence = is_string($words) ? $words : implode(' ', $words);

        // ? Process and remove common texts except the excluded types
        foreach ($cachedCommonTexts as $type => $texts) {
            if (in_array($type, $excludedTypes)) {
                continue;
            }

            foreach ($texts as $model) {
                $pattern = '/\b' . preg_quote($model->content, '/') . '\b/u';
                $wordsSentence = preg_replace($pattern, ' | ', $wordsSentence); // ? Split sentences with a special string
            }
        }

        // ? Normalize spaces, split into sentences, and get rid of singular left-over letters
        $wordsSentence = self::normalizeSpaces($wordsSentence);
        $sentences = array_filter(preg_split('/\s*\|\s*/', $wordsSentence), fn ($sentence) => mb_strlen(trim($sentence)) > 1);

        if ($asString) {
            return implode(' ', $sentences);
        } else {
            return $sentences;
        }
    }

    public function clearCommonsCache(): void
    {
        $baseCacheKey = CraftyPackage::getConfiguration('arabicable.common_arabic_text.cache_key');
        $types = CommonArabicTextType::cases();

        foreach ($types as $type) {
            $typeKey = $baseCacheKey . '.' . $type->value;
            Cache::forget($typeKey);
        }
    }
}
