<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use VPremiss\Arabicable\Facades\Arabic;

// TODO implement only and except approach
class UncommonArabic implements ValidationRule
{
    /** @param array<\VPremiss\Arabicable\Enums\CommonArabicTextType> $excludedTypes */
    public function __construct(
        protected array $excludedTypes = [],
    ) {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty(Arabic::removeCommons($value, $this->excludedTypes, asString: true))) {
            $fail("النص الخاص بحقل :attribute يحتوي على كلمات شائعة فحسب .");
        }
    }
}
