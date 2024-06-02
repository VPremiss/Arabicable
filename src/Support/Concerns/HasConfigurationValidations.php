<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Support\Concerns;

use VPremiss\Crafty\Enums\DataType;
use VPremiss\Crafty\Facades\Crafty;
use VPremiss\Crafty\Utilities\Configurated\Exceptions\ConfiguratedValidatedConfigurationException;

trait HasConfigurationValidations
{
    protected function validateSpecialCharactersConfig($value): void
    {
        if (!is_array($value) || empty($value) || count($value) !== 12) {
            throw new ConfiguratedValidatedConfigurationException(
                'The configuration special characters must be a filled array of 12 elements.'
            );
        }

        foreach ($value as $key => $innerArray) {
            if (!is_string($key)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'The configuration special characters array must have string keys. A non-string key was found!'
                );
            }

            if (!is_array($innerArray) || empty($innerArray)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'The configuration special characters array values must be arrays of strings. A non-array value was found!'
                );
            }

            if (!Crafty::validatedArray($innerArray, DataType::String)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'The configuration special characters array values must be string arrays. A non-string value was found in one of them!'
                );
            }
        }
    }

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

            if (!Crafty::validatedArray($normalizations, DataType::String)) {
                throw new ConfiguratedValidatedConfigurationException(
                    'Normalized punctuation marks array configuration contains an array value that contains a non-string value! -Sorry!'
                );
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

    protected function validateArabicPluralModelConfig($value): void
    {
        if (!is_string($value) && empty($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The Arabic plural model configuration namespace is either non-string or empty.'
            );
        }

        if (!class_exists($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The Arabic plural model configuration namespaces is not pointing to an existing class.'
            );
        }
    }

    protected function validateArabicPluralFactoryConfig($value): void
    {
        if (!is_string($value) && empty($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The Arabic plural factory configuration namespace is either non-string or empty.'
            );
        }

        if (!class_exists($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The Arabic plural factory configuration namespaces is not pointing to an existing class.'
            );
        }
    }

    protected function validateArabicPluralCacheKeyConfig($value): void
    {
        if (!is_string($value) && empty($value)) {
            throw new ConfiguratedValidatedConfigurationException(
                'The Arabic plural cache-key configuration is not a filled string.'
            );
        }
    }
}
