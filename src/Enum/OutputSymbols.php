<?php

namespace PvListManager\Enum;

use Symfony\Component\String\UnicodeString;


enum OutputSymbols: string
{
    case CheckmarkBox = "\u{2705}"; //emoji green box with white check
    case Checkmark = "\u{2713}"; //simple check
    case CheckmarkBold = "\u{2714}"; //bold check
    case XmarkHeavy = "\u{2718}";
    case Xmark = "\u{10102}";
    case Dash = "-";

    //$bs = new ByteString("\x68\x65\x6C\x6C\x6F");

    public function getString(): ?UnicodeString
    {
        return static::getUnicodeString($this);
    }

    public static function getUnicodeString(?self $value): ?UnicodeString
    {
        return match($value) {
            self::CheckmarkBox => new UnicodeString(self::CheckmarkBox->value),
            self::Checkmark => new UnicodeString((string)self::Checkmark->value),
            self::CheckmarkBold => new UnicodeString(self::CheckmarkBold->value),
            self::XmarkHeavy => new UnicodeString(self::XmarkHeavy->value),
            self::Xmark => new UnicodeString(self::Xmark->value),
            self::Dash => new UnicodeString(self::Dash->value),
        };
        //return new UnicodeString((string)$value);
    }

}