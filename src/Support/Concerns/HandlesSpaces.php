<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Support\Concerns;

use VPremiss\Arabicable\Enums\ArabicSpecialCharacters;
use VPremiss\Arabicable\Support\Exceptions\ArabicableValidationException;
use VPremiss\Crafty\Facades\CraftyPackage;

trait HandlesSpaces
{
    public function validateForTextSpacing(string $text): void
    {
        $pairMap = array_combine(
            ArabicSpecialCharacters::EnclosingStarterMarks->get(),
            ArabicSpecialCharacters::EnclosingEnderMarks->get(),
        );
        $pairMap += array_combine(
            ArabicSpecialCharacters::ArabicEnclosingStarterMarks->get(),
            ArabicSpecialCharacters::ArabicEnclosingEnderMarks->get(),
        );

        // Check paired marks
        foreach ($pairMap as $starter => $ender) {
            $starterCount = substr_count($text, $starter);
            $enderCount = substr_count($text, $ender);

            if ($starterCount !== $enderCount) {
                throw new ArabicableValidationException(
                    "Found the number of starter '$starter' not matching the number of ender '$ender' enclosing marks."
                );
            }
        }

        // Check same-type marks
        foreach (array_merge(
            ArabicSpecialCharacters::EnclosingMarks->get(),
            ArabicSpecialCharacters::ArabicEnclosingMarks->get(),
        ) as $mark) {
            if (substr_count($text, $mark) % 2 !== 0) {
                throw new ArabicableValidationException(
                    "Detected an uneven number of the enclosing mark '$mark'."
                );
            }
        }
    }

    public function normalizeSpaces(string $text): string
    {
        $text = mb_ereg_replace('^\s*|\s*$', '', $text);
        $text = preg_replace('/\s+/u', ' ', $text);

        return $text;
    }

    public function addSpacesBeforePunctuationMarks(string $text, array $inclusions = [], array $exclusions = []): string
    {
        $filteredMarks = $this->getFilteredPunctuationMarks($inclusions, $exclusions);
        $escapedMarks = array_map(fn ($mark) => preg_quote($mark, '/'), $filteredMarks);
        // Matches any of the marks and checks that the preceding character is neither a whitespace nor one of the marks.
        $pattern = "/(?<![\\s" . implode('', $escapedMarks) . "])([" . implode('', $escapedMarks) . "])/u";
        $replacement = ' $1';

        $text = preg_replace($pattern, $replacement, $text);

        return $text;
    }

    public function addSpacesAfterPunctuationMarks(string $text, array $inclusions = [], array $exclusions = []): string
    {
        // Gather all punctuation marks considering any additional inclusions and exclusions
        $filteredMarks = $this->getFilteredPunctuationMarks($inclusions, $exclusions);
        $escapedMarks = array_map(fn ($mark) => preg_quote($mark, '/'), $filteredMarks);

        // Gather all special marks (enclosing and starter marks)
        $specialMarks = arabicable_special_characters(only: [
            ArabicSpecialCharacters::EnclosingMarks,
            ArabicSpecialCharacters::EnclosingStarterMarks,
            ArabicSpecialCharacters::ArabicEnclosingMarks,
            ArabicSpecialCharacters::ArabicEnclosingStarterMarks,
        ]);
        $escapedSpecialMarks = array_map(fn ($mark) => preg_quote($mark, '/'), $specialMarks);

        // Create a single string pattern from all escaped marks for general punctuation continuity
        $allMarksPattern = implode('', $escapedMarks);

        // Create a single string pattern from all escaped special marks that should force a space after
        $specialMarksPattern = implode('', $escapedSpecialMarks);

        // Define the regex pattern
        // 1. Matches any of the general marks followed by a character that is neither a whitespace nor one of the general marks
        // 2. Adds a space unless the next character is a mark, unless it's a special mark which forces a space
        $pattern = "/([" . $allMarksPattern . "])"
            . "(?![\s" . $allMarksPattern . "])"
            . "|([" . $allMarksPattern . "])"
            . "(?=[" . $specialMarksPattern . "])/u";

        // Apply the regex and add spaces as required
        $replacement = fn ($matches) => isset($matches[2]) ? $matches[2] . ' ' : $matches[1] . ' ';

        $text = preg_replace_callback($pattern, $replacement, $text);

        return $text;
    }

    public function removeSpacesAroundPunctuationMarks(string $text, array $inclusions = [], array $exclusions = []): string
    {
        $filteredMarks = $this->getFilteredPunctuationMarks($inclusions, $exclusions);

        foreach ($filteredMarks as $mark) {
            $escapedMark = preg_quote($mark, '/');
            $pattern = "/\s*" . $escapedMark . "\s*/u";

            $text = preg_replace($pattern, $mark, $text);
        }

        return $text;
    }

    public function removeSpacesWithinEnclosingMarks(string $text, array $exclusions = []): string
    {
        $marks = array_merge(
            ArabicSpecialCharacters::EnclosingMarks->get(),
            ArabicSpecialCharacters::ArabicEnclosingMarks->get(),
        );
        $exclusions = array_merge(
            $exclusions,
            CraftyPackage::getConfiguration('arabicable.space_preserved_enclosings') ?? [],
        );
        $starterMarks = array_diff(
            array_merge(
                $marks,
                ArabicSpecialCharacters::EnclosingStarterMarks->get(),
                ArabicSpecialCharacters::ArabicEnclosingStarterMarks->get(),
            ),
            $exclusions,
        );
        $enderMarks = array_diff(
            array_merge(
                $marks,
                ArabicSpecialCharacters::EnclosingEnderMarks->get(),
                ArabicSpecialCharacters::ArabicEnclosingEnderMarks->get(),
            ),
            $exclusions,
        );

        $escapedStarterMarks = array_map(fn ($mark) => preg_quote($mark, '/'), $starterMarks);
        $escapedEnderMarks = array_map(fn ($mark) => preg_quote($mark, '/'), $enderMarks);

        // Pattern to remove space after starter marks
        $patternStarters = "/(" . implode('|', $escapedStarterMarks) . ")\s+/";
        $text = preg_replace($patternStarters, '$1', $text);

        // Pattern to remove space before ender marks
        $patternEnders = "/\s+(" . implode('|', $escapedEnderMarks) . ")/";
        $text = preg_replace($patternEnders, '$1', $text);

        return $text;
    }

    public function refineSpacesBetweenPunctuationMarks(string $text): string
    {
        if (CraftyPackage::getConfiguration('arabicable.spacing_after_punctuation_only')) {
            $enclosings = array_diff(
                $this->getAllEnclosingMarks(),
                ["'", '"', '/'],
            );
            $escapedEnclosings = array_map(fn ($mark) => preg_quote($mark, '/'), $enclosings);

            // Matches any of the specified enclosing marks not already preceded by a space
            $marksPattern = implode('|', $escapedEnclosings);
            $text = preg_replace('/(?<!\s)([' . $marksPattern . '])/u', ' $1', $text);

            // * =================
            // * Ender Enclosings
            // * ===============

            // Remove unwanted spaces before closing punctuation marks
            $enclosings = array_diff(
                array_merge(
                    ArabicSpecialCharacters::EnclosingEnderMarks->get(),
                    ArabicSpecialCharacters::ArabicEnclosingEnderMarks->get(),
                ),
                CraftyPackage::getConfiguration('arabicable.space_preserved_enclosings'),
            );
            $enclosingEndMarks = implode('', $enclosings);
            $pattern = '/\s+(?=[' . preg_quote($enclosingEndMarks) . '])/u';
            $replacement = '';
            $text = preg_replace($pattern, $replacement, $text);

            // Ensure there's a space before each dash not already preceded by a space
            $pattern = '/(?<!\s)-/';
            $replacement = ' -';
            $text = preg_replace($pattern, $replacement, $text);
        }

        // * ===============
        // * Quotes Spacing
        // * =============

        // Replace spaces around double quotes
        $text = preg_replace('/\s*"\s*([^"]*?)\s*"\s*/u', ' "$1" ', $text);
        // Replace spaces around single quotes
        $text = preg_replace("/\s*'\s*([^']*?)\s*'\s*/u", " '$1' ", $text);

        // Trim spaces around the quotes if they are at the start or end of the string
        $text = preg_replace('/^\s*"\s*/u', '"', $text);
        $text = preg_replace('/\s*"\s*$/u', '"', $text);
        $text = preg_replace("/^\s*'\s*/u", "'", $text);
        $text = preg_replace("/\s*'\s*$/u", "'", $text);

        foreach (($marks = array_merge(ArabicSpecialCharacters::PunctuationMarks->get(), ArabicSpecialCharacters::ArabicPunctuationMarks->get())) as $mark) {
            // Ensure $mark is properly quoted to escape regex special characters
            $quotedMark = preg_quote($mark, '/');

            // Adjust spaces before punctuation after a double quote
            $text = preg_replace('/"\s*' . $quotedMark . '/u', '"' . $mark, $text);

            // Adjust spaces before punctuation after a single quote
            $text = preg_replace("/'\s*" . $quotedMark . "/u", "'" . $mark, $text);
        }

        // Ensure it catches all punctuation...
        $text = preg_replace('/([' . preg_quote(implode('', $marks), '/') . '])\s*"\s*$/u', '$1"', $text);
        $text = preg_replace("/([" . preg_quote(implode('', $marks), '/') . "])\s*'\s*$/u", '$1\'', $text);

        return $text;
    }
}
