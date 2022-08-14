<?php

namespace PvListManager\Enum;

use Symfony\Component\String\UnicodeString;


enum OutputSymbols: int
{
    case Checkmark = 0x2705; //UnicodeString::fromCodePoints(0x2705)->toString();


    public static function getUnicodeString(?self $value)
    {
        return UnicodeString::fromCodePoints($value);
    }

}