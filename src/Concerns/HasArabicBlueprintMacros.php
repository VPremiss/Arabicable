<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Concerns;

use Illuminate\Database\Schema\Blueprint;

trait HasArabicBlueprintMacros
{
    public function arabicBlueprintMacros()
    {
        Blueprint::macro('indianDate', function (string $columnName, $isNullable = false, $isUnique = false) {
            $field = $this->date($columnName);
            if ($isNullable) $field->nullable();
            if ($isUnique) $field->unique();

            $indianField = $this->string(ar_indian($columnName), 10);
            if ($isNullable) $indianField->nullable();
            if ($isUnique) $indianField->unique();

            return $this;
        });

        Blueprint::macro('arabicString', function (
            string $columnName,
            $length = 255,
            $isNullable = false,
            $isUnique = false,
            $supportsFullSearch = false,
        ) {
            $supportsFullSearch = app()->environment('testing') ? false : $supportsFullSearch;

            $field = $this->string($columnName, $length);
            if ($isNullable) $field->nullable();
            if ($isUnique) $field->unique();

            $searchField = $this->string(ar_searchable($columnName), $length);
            if ($isNullable) $searchField->nullable();
            if ($supportsFullSearch) $searchField->fulltext();

            $harakatField = $this->string(ar_with_harakat($columnName), $length);
            if ($isNullable) $harakatField->nullable();

            return $this;
        });

        Blueprint::macro('arabicTinyText', function (
            string $columnName,
            $isNullable = false,
            $isUnique = false,
            $supportsFullSearch = false,
        ) {
            $supportsFullSearch = app()->environment('testing') ? false : $supportsFullSearch;
            
            $field = $this->tinyText($columnName);
            if ($isNullable) $field->nullable();
            if ($isUnique) $field->unique();

            $searchField = $this->tinyText(ar_searchable($columnName));
            if ($isNullable) $searchField->nullable();
            if ($supportsFullSearch) $searchField->fulltext();

            $harakatField = $this->tinyText(ar_with_harakat($columnName));
            if ($isNullable) $harakatField->nullable();

            return $this;
        });

        Blueprint::macro('arabicText', function (string $columnName, $isNullable = false, $isUnique = false) {
            $field = $this->text($columnName);
            if ($isNullable) $field->nullable();
            if ($isUnique) $field->unique();

            $searchField = $this->text(ar_searchable($columnName));
            if ($isNullable) $searchField->nullable();
            if (!app()->environment('testing')) $searchField->fulltext();

            $harakatField = $this->text(ar_with_harakat($columnName));
            if ($isNullable) $harakatField->nullable();

            return $this;
        });

        Blueprint::macro('arabicMediumText', function (string $columnName, $isNullable = false, $isUnique = false) {
            $field = $this->mediumText($columnName);
            if ($isNullable) $field->nullable();
            if ($isUnique) $field->unique();

            $searchField = $this->mediumText(ar_searchable($columnName));
            if ($isNullable) $searchField->nullable();
            if (!app()->environment('testing')) $searchField->fulltext();

            $harakatField = $this->mediumText(ar_with_harakat($columnName));
            if ($isNullable) $harakatField->nullable();

            return $this;
        });

        Blueprint::macro('arabicLongText', function (string $columnName, $isNullable = false, $isUnique = false) {
            $field = $this->longText($columnName);
            if ($isNullable) $field->nullable();
            if ($isUnique) $field->unique();

            $searchField = $this->longText(ar_searchable($columnName));
            if ($isNullable) $searchField->nullable();
            if (!app()->environment('testing')) $searchField->fulltext();

            $harakatField = $this->longText(ar_with_harakat($columnName));
            if ($isNullable) $harakatField->nullable();

            return $this;
        });
    }
}
