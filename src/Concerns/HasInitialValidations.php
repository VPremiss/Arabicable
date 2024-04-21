<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Concerns;

use VPremiss\Arabicable\Exceptions\ArabicableConfigurationException;

trait HasInitialValidations
{
    public function validations()
    {
        $this->validatePropertySuffixKeysConfig();
        $this->validateSpacingAfterPunctuationOnlyConfig();
        $this->validateNormalizedPunctuationMarksConfig();
        $this->validateSpacePreservedEnclosingsConfig();
        $this->validateCommonArabicTextModelConfig();
    }

    protected function validatePropertySuffixKeysConfig()
    {
        if (empty(config('arabicable.property_suffix_keys.numbers_to_indian'))) {
            throw new ArabicableConfigurationException('The configuration suffix key for Indian numerals is empty!');
        }

        if (empty(config('arabicable.property_suffix_keys.text_with_harakat'))) {
            throw new ArabicableConfigurationException('The configuration suffix key for Arabic with harakat text is empty!');
        }

        if (empty(config('arabicable.property_suffix_keys.text_for_search'))) {
            throw new ArabicableConfigurationException('The configuration suffix key for Arabic searchable text is empty!');
        }
    }

    protected function validateSpacingAfterPunctuationOnlyConfig()
    {
        if (!is_bool(config('arabicable.spacing_after_punctuation_only'))) {
            throw new ArabicableConfigurationException(
                'The boolean configuration for spacing after punctuation only is not found!'
            );
        }
    }

    protected function validateNormalizedPunctuationMarksConfig()
    {
        $normalizedPunctuationMarks = config('arabicable.normalized_punctuation_marks');

        if (!is_null($normalizedPunctuationMarks) && !is_array($normalizedPunctuationMarks)) {
            throw new ArabicableConfigurationException(
                'Normalized punctuation marks (array or null) configuration is not found.'
            );
        }

        foreach ($normalizedPunctuationMarks as $mark => $normalizations) {
            if (!is_string($mark)) {
                throw new ArabicableConfigurationException(
                    'Normalized punctuation marks array configuration contains a non-string key!'
                );
            }

            if (!is_array($normalizations)) {
                throw new ArabicableConfigurationException(
                    'Normalized punctuation marks array configuration contains a non-array value!'
                );
            }

            foreach ($normalizations as $normalization) {
                if (!is_string($normalization)) {
                    throw new ArabicableConfigurationException(
                        'Normalized punctuation marks array configuration contains an array value that contains a non-string value! -Sorry!'
                    );
                }
            }
        }
    }

    protected function validateSpacePreservedEnclosingsConfig()
    {
        $spacePreservedEnclosings = config('arabicable.space_preserved_enclosings');

        if (!is_null($spacePreservedEnclosings) && !is_array($spacePreservedEnclosings)) {
            throw new ArabicableConfigurationException(
                'Space-preserved enclosings (array or null) configuration is not found.'
            );
        }

        foreach ($spacePreservedEnclosings as $enclosing) {
            if (!is_string($enclosing)) {
                throw new ArabicableConfigurationException(
                    'Space-preserved enclosings array configuration contains a non-string value!'
                );
            }
        }
    }

    protected function validateCommonArabicTextModelConfig()
    {
        $model = config('arabicable.common_arabic_text_model');

        if (!is_null($model) && !is_string($model)) {
            throw new ArabicableConfigurationException(
                'The common Arabic text model (string or null) configuration is not found.'
            );
        }
    }
}
