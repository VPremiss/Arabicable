<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Enums;

use VPremiss\Crafty\Facades\CraftyPackage;

enum ArabicSpecialCharacters: string
{
    case Harakat = 'harakat';

    case IndianNumerals = 'indian-numerals';
    case ArabicNumerals = 'arabic-numerals';

    case PunctuationMarks = 'punctuation-marks';
    case ForeignPunctuationMarks = 'foreign-punctuation-marks';
    case ArabicPunctuationMarks = 'arabic-punctuation-marks';

    case EnclosingMarks = 'enclosing-marks';
    case EnclosingStarterMarks = 'enclosing-starter-marks';
    case EnclosingEnderMarks = 'enclosing-ender-marks';
    case ArabicEnclosingMarks = 'arabic-enclosing-marks';
    case ArabicEnclosingStarterMarks = 'arabic-enclosing-starter-marks';
    case ArabicEnclosingEnderMarks = 'arabic-enclosing-ender-marks';

    public function get(): array
    {
        return match ($this) {
            self::Harakat =>
                CraftyPackage::getConfiguration('arabicable.special_characters.harakat'),

            self::IndianNumerals =>
                CraftyPackage::getConfiguration('arabicable.special_characters.indian_numerals'),
            self::ArabicNumerals =>
                CraftyPackage::getConfiguration('arabicable.special_characters.arabic_numerals'),

            self::PunctuationMarks =>
                CraftyPackage::getConfiguration('arabicable.special_characters.punctuation_marks'),
            self::ForeignPunctuationMarks =>
                CraftyPackage::getConfiguration('arabicable.special_characters.foreign_punctuation_marks'),
            self::ArabicPunctuationMarks =>
                CraftyPackage::getConfiguration('arabicable.special_characters.arabic_punctuation_marks'),

            self::EnclosingMarks => 
                CraftyPackage::getConfiguration('arabicable.special_characters.enclosing_marks'),
            self::EnclosingStarterMarks => 
                CraftyPackage::getConfiguration('arabicable.special_characters.enclosing_starter_marks'),
            self::EnclosingEnderMarks =>
                CraftyPackage::getConfiguration('arabicable.special_characters.enclosing_ender_marks'),
            self::ArabicEnclosingMarks =>
                CraftyPackage::getConfiguration('arabicable.special_characters.arabic_enclosing_marks'),
            self::ArabicEnclosingStarterMarks =>
                CraftyPackage::getConfiguration('arabicable.special_characters.arabic_enclosing_starter_marks'),
            self::ArabicEnclosingEnderMarks =>
                CraftyPackage::getConfiguration('arabicable.special_characters.arabic_enclosing_ender_marks'),
        };
    }
}
