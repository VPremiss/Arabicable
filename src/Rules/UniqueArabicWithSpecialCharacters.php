<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueArabicWithSpecialCharacters implements ValidationRule
{
    public function __construct(
        protected string $modelClass,
        protected ?string $propertyName = null,
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $model = $this->modelClass;
        $property = $this->propertyName ?? $attribute;

        if (!class_exists($model)) {
            $fail("The model class '{$model}' for :attribute does not exist.");
            return;
        }

        if (!method_exists($model, 'getTable') || empty($table = $model::getTable())) {
            $fail("The model '$model' for :attribute does not have getTable method.");
            return;
        }

        if (!DB::getSchemaBuilder()->hasColumn($table, $property)) {
            $fail("The table '$table' does not have :attribute column.");
            return;
        }

        // TODO ARE YOU SURE?
        $model::whereFuzzy($property, $value)
            ->get()
            ->each(function ($instance) use ($fail, $property) {
                if ($instance->{"fuzzy_relevance_{$property}"} === 270) {
                    $fail("A match for :attribute was found, therefore it's not unique.");
                }
            });
    }
}
