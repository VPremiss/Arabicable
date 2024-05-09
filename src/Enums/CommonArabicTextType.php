<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Enums;

enum CommonArabicTextType: string
{
    case Separator = 'separator';
    case Verb = 'verb';
    case Noun = 'noun';
    case Name = 'name';
    case Sentence = 'sentence';
}
