<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Support\Concerns;

use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasConfigurationValidations
{
    protected function validatePropertySuffixKeysNumbersToIndianConfig($value): void
    {
        if (empty($value) || !is_string($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration suffix key for Indian numerals must be a filled string.'
            );
        }
    }

    protected function validatePropertySuffixKeysTextWithHarakatConfig($value): void
    {
        if (empty($value) || !is_string($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration suffix key for Arabic with harakat text must be a filled string.'
            );
        }
    }

    protected function validatePropertySuffixKeysTextForSearchConfig($value): void
    {
        if (empty($value) || !is_string($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration suffix key for Arabic searchable text must be a filled string.'
            );
        }
    }

    protected function validateSpacingAfterPunctuationOnlyConfig($value): void
    {
        if (!is_bool($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The boolean configuration for spacing after punctuation only is not found!'
            );
        }
    }

    protected function validateNormalizedPunctuationMarksConfig($value): void
    {
        if (!is_null($value) && !is_array($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'Normalized punctuation marks (array or null) configuration is not found.'
            );
        }

        foreach ($value as $mark => $normalizations) {
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

    protected function validateSpacePreservedEnclosingsConfig($value): void
    {
        if (!is_null($value) && !is_array($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'Space-preserved enclosings (array or null) configuration is not found.'
            );
        }

        foreach ($value as $enclosing) {
            if (!is_string($enclosing)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'Space-preserved enclosings array configuration contains a non-string value!'
                );
            }
        }
    }

    protected function validateCommonArabicTextModelConfig($value): void
    {
        if (!is_string($value) && empty($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The common Arabic text model configuration namespace is either non-string or empty.'
            );
        }

        if (!class_exists($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The common Arabic text model configuration namespaces is not pointing to an existing class.'
            );
        }
    }

    protected function validateCommonArabicTextFactoryConfig($value): void
    {
        if (!is_string($value) && empty($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The common Arabic text factory configuration namespace is either non-string or empty.'
            );
        }

        if (!class_exists($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The common Arabic text factory configuration namespaces is not pointing to an existing class.'
            );
        }
    }

    protected function validateCommonArabicTextCacheKeyConfig($value): void
    {
        if (!is_string($value) && empty($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The common Arabic text cache-key configuration is not a filled string.'
            );
        }
    }
}
