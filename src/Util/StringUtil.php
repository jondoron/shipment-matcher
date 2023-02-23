<?php

namespace Util;

class StringUtil
{
    public const vowels = ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"];
    public static function removeVowelsAndSpaces(string $string): string
    {
        return str_replace(array_merge(self::vowels, [" "]), "", $string);
    }
}
