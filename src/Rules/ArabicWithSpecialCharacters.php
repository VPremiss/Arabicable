<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use VPremiss\Arabicable\Enums\ArabicSpecialCharacters;
use VPremiss\Arabicable\Support\Exceptions\ArabicableRuleException;
use VPremiss\Crafty\Enums\DataType;
use VPremiss\Crafty\Facades\Crafty;

class ArabicWithSpecialCharacters implements ValidationRule
{
    public function __construct(
        /** Either */
        protected ArabicSpecialCharacters|array $except = [],
        /** Or */
        protected ArabicSpecialCharacters|array $only = [],
    ) {
        if (
            (
                filled($this->except)
                && !Crafty::validatedArray(Arr::wrap($this->except), DataType::SpecificEnum(ArabicSpecialCharacters::class))
            ) || (
                filled($this->only)
                && !Crafty::validatedArray(Arr::wrap($this->only), DataType::SpecificEnum(ArabicSpecialCharacters::class))
            )
        ) {
            throw new ArabicableRuleException(
                "Only ArabicSpecialCharacters enum cases are allowed for 'except' and 'only' properties."
            );
        }
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $specialCharacterCases = Crafty::filterProps(ArabicSpecialCharacters::cases(), $this->only, $this->except);

        $arabicLetters = 'اأإآبتثجحخدذرزسشصضطظعغفقكلمنهويىءئة';
        $specialCharacters = collect($specialCharacterCases)
            ->map(fn ($item) => $item->get())
            ->collapse()
            ->toArray();

        // ? Matching Arabic characters and the special characters; plus spaces
        $pattern = '/^[' . preg_quote($arabicLetters . implode('', $specialCharacters), '/') . '\s]+$/u';

        if (!preg_match($pattern, $value)) {
            $fail("يجب أن يحتوي حقل :attribute على الأحرف العربية والعلامات الخاصة الموضحة فقط .");
        }
    }
}
