<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Property suffix keys (array) [amount of 3]
     |--------------------------------------------------------------------------
     |
     | These are the keys used to append to Arabic migration property names.
     |
     */

    'property_suffix_keys' => [
        'numbers_to_indian' => '_indian',
        'text_with_harakat' => '_with_harakat',
        'text_for_search' => '_searchable',
        // ? Remember that the normal property name holds content without harakat
    ],

];
