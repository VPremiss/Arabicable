<?php

declare(strict_types=1);

return [

    /*
     |--------------------------------------------------------------------------
     | Property suffix keys (array<string, string>) [3 entries]
     |--------------------------------------------------------------------------
     |
     | These are the keys used to append to Arabic migration property names.
     |
     */

    'property_suffix_keys' => [
        'numbers_to_indian' => '_indian',
        'text_with_harakat' => '_with_harakat', // ? Remember: the original property_name holds content without harakat
        'text_for_search' => '_searchable',
    ],

    /*
     |--------------------------------------------------------------------------
     | Spacing after punctuation marks only (bool)
     |--------------------------------------------------------------------------
     |
     | Determine whether you want spaces only after punctuation marks, NOT before
     | them. Don't worry about enclosing marks, they'll still have a space before.
     |
     */

    'spacing_after_punctuation_only' => false,

    /*
     |--------------------------------------------------------------------------
     | Normalized punctuation marks (null|array<string, array<string>>)
     |--------------------------------------------------------------------------
     |
     | Decide whether to convert punctuation marks into standard ones. Setting
     | to `null` would obviously disable the conversion.
     |
     */

    'normalized_punctuation_marks' => [
        '«' => ['<', '<<'],
        '»' => ['>', '>>'],
        // '⦗' => ['{', '﴾'],
        // '⦘' => ['}', '﴿'],
    ],

    /*
     |--------------------------------------------------------------------------
     | Space-Preserved Enclosing Marks (null|array<string>)
     |--------------------------------------------------------------------------
     |
     | Choose enclosing marks which will have space within them and around their
     | content. Setting to `null` would obviously disable the conversion.
     |
     | Should exist among the constants in Arabic and Text classes. PR for more.
     |
     */

    'space_preserved_enclosings' => [
        '{', '}',
        // '«', '»',
    ],

    /*
     |--------------------------------------------------------------------------
     | Common Arabic Text (array<string, string>)
     |--------------------------------------------------------------------------
     |
     | Customize the common Arabic text concept's configurations, which can be utilized
     | for search-filtering purposes.
     |
     | The cache-key is used to save CommonArabicText records already sorted result.
     | It is reset in the last line step in its seeder; which function
     | (Arabic::clearCache()) can also be used from anywhere else.
     |
     */

    'common_arabic_text' => [
        'model' => \VPremiss\Arabicable\Models\CommonArabicText::class,
        'factory' => \VPremiss\Arabicable\Database\Factories\CommonArabicTextFactory::class,
        'cache_key' => 'common_arabic_texts',
    ],

    /*
     |--------------------------------------------------------------------------
     | Arabic Plurals (array<string, string>) [3 entries]
     |--------------------------------------------------------------------------
     |
     | Customize the Arabic plural concept's configurations, which can be utilized
     | for search-enhancement purposes.
     |
     | The cache-key is used to save ArabicPlural records. It is reset in the last
     | line step in its seeder; which function (Arabic::clearCache()) can also be
     | used from anywhere else.
     |
     */

     'arabic_plural' => [
        'model' => \VPremiss\Arabicable\Models\ArabicPlural::class,
        'factory' => \VPremiss\Arabicable\Database\Factories\ArabicPluralFactory::class,
        'cache_key' => 'arabic_plurals',
    ],

];
