<?php

declare(strict_types=1);

use VPremiss\Arabicable\Enums\ArabicSpecialCharacters;
use VPremiss\Arabicable\Rules\Arabic;
use VPremiss\Arabicable\Rules\ArabicWithSpecialCharacters;

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
