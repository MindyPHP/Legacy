<?php

namespace Mindy\Helper;

/**
 * Class Text
 * @package Mindy\Helper
 */
class Text
{
    public static function rightLetter($str)
    {
        return strtr($str, [
            "q" => "й", "w" => "ц", "e" => "у", "r" => "к",
            "t" => "е", "y" => "н", "u" => "г", "i" => "ш", "o" => "щ",
            "p" => "з", "[" => "х", "]" => "ъ", "a" => "ф", "s" => "ы",
            "d" => "в", "f" => "а", "g" => "п", "h" => "р", "j" => "о",
            "k" => "л", "l" => "д", ";" => "ж", "'" => "э", "z" => "я",
            "x" => "ч", "c" => "с", "v" => "м", "b" => "и", "n" => "т",
            "m" => "ь", "," => "б", "." => "ю"
        ]);
    }

    public static function limit($text, $length, $escape = true, $last = '...')
    {
        if ($escape) {
            $text = strip_tags($text);
        }
        if (mb_strlen($text, "UTF-8") > $length) {
            return mb_substr($text, 0, $length, "UTF-8") . $last;
        } else {
            return $text;
        }
    }

    public static function revertLimit($text, $length, $escape = true, $last = '...', $first = '')
    {
        if ($escape) {
            $text = strip_tags($text);
        }
        if (mb_strlen($text, "UTF-8") > $length) {
            return $first . mb_substr($text, mb_strlen($text, "UTF-8") - $length, $length, "UTF-8") . $last;
        } else {
            return $text;
        }
    }

    public static function limitword($text, $limit, $ends = '...')
    {
        if (mb_strlen($text, 'utf-8') > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);

            if (isset($pos[$limit])) {
                $text = mb_substr($text, 0, $pos[$limit], 'utf-8') . $ends;
            }
        }
        return $text;
    }

    public static function mbUcfirst($word)
    {
        return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), 1, mb_strlen($word, 'UTF-8'), 'UTF-8');
    }

    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || mb_strpos($haystack, $needle, 0, 'UTF-8') === 0;
    }

    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || mb_strrpos($haystack, $needle, 0, 'UTF-8') === mb_strlen($haystack, 'UTF-8') - mb_strlen($needle, 'UTF-8');
    }

    /**
     * Returns given word as CamelCased
     * Converts a word like "send_email" to "SendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "WhoSOnline"
     * @see variablize()
     * @param string $word the word to CamelCase
     * @return string
     */
    public static function toCamelCase($word)
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^A-Za-z0-9]+/', ' ', $word))));
    }

    /**
     * Converts any "CamelCased" into an "underscored_word".
     * @param string $words the word(s) to underscore
     * @return string
     */
    public static function toUnderscore($words)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $words));
    }
}
