<?php

namespace VPremiss\Arabicable;

// TODO consider some python pyarabic package methods and consts maybe?
class Arabic extends Text
{
    public const HARAKAT = ['ِ', 'ُ', 'ٓ', 'ٰ', 'ْ', 'ٌ', 'ٍ', 'ً', 'ّ', 'َ', 'ـ', 'ٗ'];

    public const ARABIC_PUNCTUATION_MARKS = ['؟', '،', '؛'];

    public function removeHarakat(string $text): string
    {
        return strtr($text, array_fill_keys(self::HARAKAT, ''));
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

    public function convertToFasila(string $text): string
    {
        return strtr($text, [',' => '،']);
    }
}
