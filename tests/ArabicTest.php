<?php

declare(strict_types=1);

use VPremiss\Arabicable\ArabicableServiceProvider;
use VPremiss\Arabicable\Facades\Arabic;
use VPremiss\Arabicable\Models\ArabicPlural;
use VPremiss\Arabicable\Models\CommonArabicText;
use VPremiss\Crafty\Facades\CraftyPackage;

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
