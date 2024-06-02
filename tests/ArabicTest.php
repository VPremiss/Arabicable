<?php

declare(strict_types=1);

use VPremiss\Arabicable\ArabicableServiceProvider;
use VPremiss\Arabicable\Facades\Arabic;
use VPremiss\Arabicable\Models\ArabicPlural;
use VPremiss\Arabicable\Models\CommonArabicText;
use VPremiss\Crafty\Facades\CraftyPackage;

describe('HandlesNumerals trait → convertNumeralsToIndian function', function () {
    it('can convert individual Arabic numerals to Indian ones', function () {
        $arabicNumerals = '1 2 3 4 5';
        $indianNumerals = '١ ٢ ٣ ٤ ٥';
    
        expect($indianNumerals)->toBe(Arabic::convertNumeralsToIndian($arabicNumerals));
    });

    it('can convert grouped Arabic numerals to Indian sequences', function () {
        $arabicNumerals = '1 2 3 4 56';
        $indianNumerals = '١ ٢ ٣ ٤ ٥٦';
    
        expect($indianNumerals)->toBe(Arabic::convertNumeralsToIndian($arabicNumerals));
    });
});

describe('HandlesNumerals trait → convertNumeralsToArabicAndIndianSequences function', function () {
    it('can duplicate numerals to both Arabic and Indian individual numerals', function () {
        $nemerals = '1 2 3 4 5';
        $expectation = '1 ١ 2 ٢ 3 ٣ 4 ٤ 5 ٥';

        expect($expectation)->toBe(
            Arabic::convertNumeralsToArabicAndIndianSequences($nemerals)
        );
    });

    it('can duplicate grouped numeral sequences to both Arabic and Indian ones', function () {
        $nemerals = '11 2222 3 40 5';
        $expectation = '11 ١١ 2222 ٢٢٢٢ 3 ٣ 40 ٤٠ 5 ٥';

        expect($expectation)->toBe(
            Arabic::convertNumeralsToArabicAndIndianSequences($nemerals)
        );
    });
});

describe('HandlesNumerals trait → deduplicateArabicAndIndianNumeralSequences function', function () {   
    it('can remove duplicated individual numerals that were caused by filtering', function () {
        $nemerals = '1 ١ 1 ١ 2 ٢ 2 ٢ 3 ٣ 3 ٣ 4 ٤ 4 ٤ 5 ٥ 5 ٥';
        $expectation = '1 ١ 2 ٢ 3 ٣ 4 ٤ 5 ٥';
    
        expect($expectation)->toBe(
            trim(Arabic::deduplicateArabicAndIndianNumeralSequences($nemerals))
        );
    });

    it('can remove duplicated sequential numerals that were caused by filtering', function () {
        $nemerals = '11 ١١ 11 ١١ 2222 ٢٢٢٢ 2222 ٢٢٢٢ 3 ٣ 3 ٣ 40 ٤٠ 40 ٤٠ 5 ٥ 5 ٥';
        $expectation = '11 ١١ 2222 ٢٢٢٢ 3 ٣ 40 ٤٠ 5 ٥';
    
        expect($expectation)->toBe(
            trim(Arabic::deduplicateArabicAndIndianNumeralSequences($nemerals))
        );
    });
});

describe('HandlesPunctuation trait → convertPunctuationMarksToArabic function', function () {
    it('can convert foreign punctuation marks into Arabic ones', function () {
        $englishThought = 'What is the question, I wonder; probably?';
        $arabicishThought = 'What is the question، I wonder؛ probably؟';
    
        expect($arabicishThought)->toBe(Arabic::convertPunctuationMarksToArabic($englishThought));
    });
});

describe('HandlesPunctuation trait → removeAllPunctuationMarks function', function () {
    it('can remove ALL punctuation marks altogether', function () {
        $annoyingStyle = 'So... Any news, I hope?! Or not so much...?!';
        $peskyingGone = 'So Any news I hope Or not so much';
    
        expect($peskyingGone)->toBe(Arabic::removeAllPunctuationMarks($annoyingStyle));
    });
});

it('can filter out Arabic harakat', function () {
    $textWithHarakat = 'بِسْمِ اللَّه';
    $textWithoutHarakat = 'بسم الله';

    expect($textWithoutHarakat)->toBe(Arabic::removeHarakat($textWithHarakat));
});

it('can normalize some Arabic huroof in order to make search consistant', function () {
    $originalText = 'أقبل الولد';
    $normalizedText = 'اقبل الولد';

    expect($normalizedText)->toBe(Arabic::normalizeHuroof($originalText));

    $originalText = 'قصارى الجهد';
    $normalizedText = 'قصارا الجهد';

    expect($normalizedText)->toBe(Arabic::normalizeHuroof($originalText));
});

it('can filter out common Arabic text', function () {
    CraftyPackage::seed(ArabicableServiceProvider::class, 'CommonArabicTextSeeder');

    expect(CommonArabicText::count())->toBeGreaterThan(0);

    $words = [
        "ومن",
        "سن",
        "في",
        "الإسلام",
        "سنة",
        "سيئة",
        "فعليه",
        "وزرها",
        "ووزر",
        "من",
        "يعمل",
        "بها",
        "من",
        "غير",
        "أن",
        "ينتقص",
        "من",
        "أوزارهم",
        "شيئا",
    ];
    $expectation = [
        "سن",
        "الإسلام سنة سيئة فعليه وزرها ووزر",
        "يعمل",
        "ينتقص",
        "أوزارهم",
    ];

    $filteredWords = Arabic::removeCommons($words);

    expect($filteredWords)->toEqualCanonicalizing($expectation);
});

it('can extract Arabic plurals from singulars and vice-versa', function () {
    CraftyPackage::seed(ArabicableServiceProvider::class, 'ArabicPluralSeeder');

    expect(ArabicPlural::count())->toBeGreaterThan(0);

    $singulars = [
        'يمين',
        'أثر',
    ];
    $plurals = [
        'أيمن',
        'أيمان',
        'آثار',
    ];

    $foundPlurals = Arabic::getPlurals($singulars);

    expect($plurals)->toEqualCanonicalizing($foundPlurals);

    $plurals = [
        'أيمن',
        'أيمان',
        'آثار',
    ];
    $singulars = [
        'يمين',
        'أثر',
    ];

    $foundSingulars = Arabic::getSingulars($plurals);

    expect($singulars)->toEqualCanonicalizing($foundSingulars);
});

// TODO test the cache cleaner
