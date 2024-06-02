<?php

declare(strict_types=1);

use VPremiss\Arabicable\ArabicableServiceProvider;
use VPremiss\Arabicable\Enums\ArabicSpecialCharacters;
use VPremiss\Arabicable\Enums\CommonArabicTextType;
use VPremiss\Arabicable\Rules\Arabic;
use VPremiss\Arabicable\Rules\ArabicWithSpecialCharacters;
use VPremiss\Arabicable\Rules\UncommonArabic;
use VPremiss\Crafty\Facades\CraftyPackage;

describe('Arabic rule', function () {
    it('validates against non-arabic letters only', function () {
        $arabic = 'بسم الله';
        $withHarakat = 'بِسْمِ اللَّهِ نبدأ';
        $withPunctuationToo = 'بِسْمِ اللَّهِ نبدأ ، وهكذا ...!';

        expect(validator()->make(['text' => $arabic], ['text' => new Arabic])->fails())->toBeFalse();
        expect(validator()->make(['text' => $withHarakat], ['text' => new Arabic])->fails())->toBeTrue();
        expect(validator()->make(['text' => $withPunctuationToo], ['text' => new Arabic])->fails())->toBeTrue();
    });

    it('validates against non-arabic letters and harakat only', function () {
        $arabic = 'بسم الله';
        $withHarakat = 'بِسْمِ اللَّهِ نبدأ';
        $withPunctuationToo = 'بِسْمِ اللَّهِ نبدأ ، وهكذا ...!';

        expect(
            validator()->make(['text' => $arabic], ['text' => new Arabic(withHarakat: true)])->fails()
        )->toBeFalse();
        expect(
            validator()->make(['text' => $withHarakat], ['text' => new Arabic(withHarakat: true)])->fails()
        )->toBeFalse();
        expect(
            validator()->make(['text' => $withPunctuationToo], ['text' => new Arabic(withHarakat: true)])->fails()
        )->toBeTrue();
    });

    it('validates against non-arabic letters harakat and punctuation only', function () {
        $arabic = 'بسم الله';
        $withHarakat = 'بِسْمِ اللَّهِ نبدأ';
        $withPunctuationToo = 'بِسْمِ اللَّهِ نبدأ ، وهكذا ...!';

        expect(
            validator()->make(['text' => $arabic], ['text' => new Arabic(
                withHarakat: true,
                withPunctuation: true,
            )])->fails()
        )->toBeFalse();
        expect(
            validator()->make(['text' => $withHarakat], ['text' => new Arabic(
                withHarakat: true,
                withPunctuation: true,
            )])->fails()
        )->toBeFalse();
        expect(
            validator()->make(['text' => $withPunctuationToo], ['text' => new Arabic(
                withHarakat: true,
                withPunctuation: true,
            )])->fails()
        )->toBeFalse();
    });
});

describe('ArabicWithSpecialCharacters rule', function () {
    it('validates against non-arabic letters only', function () {
        $arabic = 'بسم الله';
        $withHarakat = 'بِسْمِ اللَّهِ نبدأ';
        $withPunctuationToo = 'بِسْمِ اللَّهِ نبدأ ، وهكذا ...!';

        expect(
            validator()->make(['text' => $arabic], ['text' => new ArabicWithSpecialCharacters])->fails()
        )->toBeFalse();
        expect(
            validator()->make(['text' => $withHarakat], ['text' => new ArabicWithSpecialCharacters])->fails()
        )->toBeFalse();
        expect(
            validator()->make(['text' => $withPunctuationToo], ['text' => new ArabicWithSpecialCharacters])->fails()
        )->toBeFalse();

        $english = 'Oh, you, The Resider of the Sky... Please forgive me.';
        $nihonjin = 'おお天国の住人よ、許してください';

        expect(
            validator()->make(['text' => $english], ['text' => new ArabicWithSpecialCharacters])->fails()
        )->toBeTrue();
        expect(
            validator()->make(['text' => $nihonjin], ['text' => new ArabicWithSpecialCharacters])->fails()
        )->toBeTrue();
    });

    it('validates against non-arabic letters and the allowed special characters only', function () {
        $arabic = 'بسم الله';
        $withHarakat = 'بِسْمِ اللَّهِ نبدأ';
        $withPunctuationToo = 'بِسْمِ اللَّهِ نبدأ ، وهكذا ...!';

        expect(
            validator()->make(['text' => $arabic], ['text' => new ArabicWithSpecialCharacters(only: [])])->fails()
        )->toBeFalse();
        expect(
            validator()->make(['text' => $withHarakat], [
                'text' => new ArabicWithSpecialCharacters(except: [ArabicSpecialCharacters::Harakat]),
            ])->fails()
        )->toBeTrue();
        expect(
            validator()->make(['text' => $withHarakat], [
                'text' => new ArabicWithSpecialCharacters(only: [ArabicSpecialCharacters::Harakat]),
            ])->fails()
        )->toBeFalse();
        expect(
            validator()->make(['text' => $withPunctuationToo], [
                'text' => new ArabicWithSpecialCharacters(only: [
                    ArabicSpecialCharacters::Harakat,
                    ArabicSpecialCharacters::PunctuationMarks,
                    ArabicSpecialCharacters::ForeignPunctuationMarks,
                    ArabicSpecialCharacters::ArabicPunctuationMarks,
                ]),
            ])->fails()
        )->toBeFalse();

        $english = 'Oh, you, The Resider of The Sky... Please forgive me.';
        $nihonjin = 'おお天国の住人よ、許してください';

        expect(
            validator()->make(['text' => $english], ['text' => new ArabicWithSpecialCharacters])->fails()
        )->toBeTrue();
        expect(
            validator()->make(['text' => $nihonjin], ['text' => new ArabicWithSpecialCharacters])->fails()
        )->toBeTrue();
    });
});

describe('UncommonArabic rule', function () {
    it('validates against common Arabic of all or specific types', function () {
        CraftyPackage::seed(ArabicableServiceProvider::class, 'CommonArabicTextSeeder');

        $common = 'هو كان كذا';

        expect(validator()->make(['common' => $common], ['common' => new UncommonArabic])->fails())->toBeTrue();
        expect(
            validator()->make(['common' => $common], ['common' => new UncommonArabic([
                CommonArabicTextType::Separator,
            ])])->fails()
        )->toBeFalse();

        $uncommon = 'من لم يدع قول الزور والعمل به والجهل ، فليس لله حاجة في أن يدع طعامه وشرابه';

        expect(validator()->make(['common' => $uncommon], ['common' => new UncommonArabic])->fails())->toBeFalse();
    });
});
