<?php

namespace VPremiss\Arabicable\Concerns;

use Illuminate\Database\Schema\Blueprint;

trait HasArabicBlueprintMacros
{
    public function arabicBlueprintMacros()
    {
        Blueprint::macro('indianDate', function (string $columnName, $isNullable = false, $isUnique = false) {
            $this->date($columnName)
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($isUnique, fn ($field) => $field->unique());

            $this->string(ar_indian($columnName), 10)
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($isUnique, fn ($field) => $field->unique());

            return $this;
        });

        Blueprint::macro('arabicString', function (
            string $columnName,
            $length = 255,
            $isNullable = false,
            $isUnique = false,
            $supportsFullSearch = false,
        ) {
            $this->string($columnName, $length)
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($isUnique, fn ($field) => $field->unique());

            $this->string(ar_searchable($columnName), $length)
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($supportsFullSearch, fn ($field) => $field->fulltext());

            $this->string(ar_with_harakat($columnName), $length)
                ->when($isNullable, fn ($field) => $field->nullable());

            return $this;
        });

        Blueprint::macro('arabicTinyText', function (
            string $columnName,
            $isNullable = false,
            $isUnique = false,
            $supportsFullSearch = false,
        ) {
            $this->tinyText($columnName)
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($isUnique, fn ($field) => $field->unique());

            $this->tinyText(ar_searchable($columnName))
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($supportsFullSearch, fn ($field) => $field->fulltext());

            $this->tinyText(ar_with_harakat($columnName))
                ->when($isNullable, fn ($field) => $field->nullable());

            return $this;
        });

        Blueprint::macro('arabicText', function (string $columnName, $isNullable = false, $isUnique = false) {
            $this->text($columnName)
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($isUnique, fn ($field) => $field->unique());

            $this->text(ar_searchable($columnName))
                ->when($isNullable, fn ($field) => $field->nullable())
                ->fulltext();

            $this->text(ar_with_harakat($columnName))
                ->when($isNullable, fn ($field) => $field->nullable());

            return $this;
        });

        Blueprint::macro('arabicMediumText', function (string $columnName, $isNullable = false, $isUnique = false) {
            $this->mediumText($columnName)
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($isUnique, fn ($field) => $field->unique());

            $this->mediumText(ar_searchable($columnName))
                ->when($isNullable, fn ($field) => $field->nullable())
                ->fulltext();

            $this->mediumText(ar_with_harakat($columnName))
                ->when($isNullable, fn ($field) => $field->nullable());

            return $this;
        });

        Blueprint::macro('arabicLongText', function (string $columnName, $isNullable = false, $isUnique = false) {
            $this->longText($columnName)
                ->when($isNullable, fn ($field) => $field->nullable())
                ->when($isUnique, fn ($field) => $field->unique());

            $this->longText(ar_searchable($columnName))
                ->when($isNullable, fn ($field) => $field->nullable())
                ->fulltext();

            $this->longText(ar_with_harakat($columnName))
                ->when($isNullable, fn ($field) => $field->nullable());

            return $this;
        });
    }
}
