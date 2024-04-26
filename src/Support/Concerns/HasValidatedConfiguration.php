<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Support\Concerns;

use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasValidatedConfiguration
{
    protected function validatePropertySuffixKeysConfig()
    {
        if (
            empty($key = CraftyPackage::config('arabicable.property_suffix_keys.numbers_to_indian', $this))
            || !is_string($key)
        ) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration suffix key for Indian numerals must be a filled string.'
            );
        }

        if (
            empty($key = CraftyPackage::config('arabicable.property_suffix_keys.text_with_harakat', $this))
            || !is_string($key)
        ) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration suffix key for Arabic with harakat text must be a filled string.'
            );
        }

        if (
            empty($key = CraftyPackage::config('arabicable.property_suffix_keys.text_for_search', $this))
            || !is_string($key)
        ) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration suffix key for Arabic searchable text must be a filled string.'
            );
        }
    }

    protected function validateSpacingAfterPunctuationOnlyConfig()
    {
        if (!is_bool(CraftyPackage::config('arabicable.spacing_after_punctuation_only', $this))) {
            throw new ConfiguratedValidatedConfigurationException(
                'The boolean configuration for spacing after punctuation only is not found!'
            );
        }
    }

    protected function validateNormalizedPunctuationMarksConfig()
    {
        $normalizedPunctuationMarks = CraftyPackage::config('arabicable.normalized_punctuation_marks', $this);

        if (!is_null($normalizedPunctuationMarks) && !is_array($normalizedPunctuationMarks)) {
            throw new ConfiguratedValidatedConfigurationException(
                'Normalized punctuation marks (array or null) configuration is not found.'
            );
        }

        foreach ($normalizedPunctuationMarks as $mark => $normalizations) {
            if (!is_string($mark)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'Normalized punctuation marks array configuration contains a non-string key!'
                );
            }

            if (!is_array($normalizations)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'Normalized punctuation marks array configuration contains a non-array value!'
                );
            }

            foreach ($normalizations as $normalization) {
                if (!is_string($normalization)) {
                    throw new ConfiguratedValidatedConfigurationException(
                        'Normalized punctuation marks array configuration contains an array value that contains a non-string value! -Sorry!'
                    );
                }
            }
        }
    }

    protected function validateSpacePreservedEnclosingsConfig()
    {
        $spacePreservedEnclosings = CraftyPackage::config('arabicable.space_preserved_enclosings', $this);

        if (!is_null($spacePreservedEnclosings) && !is_array($spacePreservedEnclosings)) {
            throw new ConfiguratedValidatedConfigurationException(
                'Space-preserved enclosings (array or null) configuration is not found.'
            );
        }

        foreach ($spacePreservedEnclosings as $enclosing) {
            if (!is_string($enclosing)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'Space-preserved enclosings array configuration contains a non-string value!'
                );
            }
        }
    }

    protected function validateCommonArabicTextConfig()
    {
        $array = CraftyPackage::config('arabicable.common_arabic_text', $this);

        if (!is_null($array) && !is_array($array) && !filled($array)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The common Arabic text (filled array or null) configuration is not found.'
            );
        }

        foreach ($array as $namespace) {
            if (!is_string($namespace) && empty($namespace)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'One of the common Arabic text configuration namespaces is either non-string or empty.'
                );
            }

            if (!class_exists($namespace)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'One of the common Arabic text configuration namespaces is not pointing to an existing class.'
                );
            }
        }
    }
}
