<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Observers;

use Illuminate\Support\Facades\Schema;
use VPremiss\Arabicable\Facades\Arabic;
use VPremiss\Arabicable\Facades\ArabicFilter;

class ModelArabicObserver
{
    public function creating($model)
    {
        $this->handleArabicAttributes($model);
    }

    public function updating($model)
    {
        $this->handleArabicAttributes($model, true);
    }

    protected function handleArabicAttributes($model, $checkDirty = false)
    {
        $tableName = $model->getTable();
        $attributes = $model->getAttributes();

        foreach ($attributes as $key => $value) {
            if (!$checkDirty || $model->isDirty($key)) {
                if ($this->shouldHandleArabic($tableName, $key)) {
                    $this->updateArabicAttributes($model, $key, $value);
                } elseif ($this->shouldHandleIndianNumbers($tableName, $key)) {
                    $model->{ar_indian($key)} = Arabic::convertNumeralsToIndian($value);
                }
            }
        }
    }

    protected function shouldHandleArabic($tableName, $key)
    {
        return Schema::hasColumn($tableName, ar_with_harakat($key))
            && Schema::hasColumn($tableName, ar_searchable($key));
    }

    protected function shouldHandleIndianNumbers($tableName, $key)
    {
        return Schema::hasColumn($tableName, ar_indian($key));
    }

    protected function updateArabicAttributes($model, $key, $value)
    {
        $model->{ar_with_harakat($key)} = ArabicFilter::withHarakat($value);
        $model->{ar_searchable($key)} = ArabicFilter::forSearch($value);
        $model->{$key} = ArabicFilter::withoutHarakat($value);
    }
}
