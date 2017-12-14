<?php

namespace avtomon;

class searchSyntax2PgException extends \Exception
{
}

class searchSyntax2Pg
{
    public static function getPgSyntax(string $search, string $syntaxModel = 'google'):string
    {
        $search = trim($search);
        if (!$search || !preg_match_all('/(-?[^\s"]+)|"(.+?)"/i', $search, $matches)) {
            throw new googleSearchSyntax2PgException('Поисковая фраза не содержит слов');
        }

        switch ($syntaxModel) {
            case 'google':
                $condition = strpos($match, ' ') !== false;
                break;

            case 'yandex':
                $condition = strpos($match, ' ') !== false || $match[0] === '!';
                break;

            default:
                throw new googleSearchSyntax2PgException("Модель синтаксиса $syntaxModel не поддерживается");
        }

        $result = [];
        foreach ($matches as $index => $match) {
            if ($condition) {
                $result[] = self::getExactFromGoogle($match) . '::tsquery';
                continue;
            }

            if ($match == 'OR') {
                if ($index != 0 && !empty($matches[$index + 1])) {
                    $result[] = '||';
                }
                continue;
            }

            $result[] = "to_tsuqery('" . str_replace('-', '!', $match) . "')";

            if (!empty($matches[$index + 1])) {
                $result[] = '&&';
            }
        }

        return $result;
    }

    public static function getExactFromGoogle(string $str):array
    {
        $str = trim($str);
        if (!$str) {
            throw new googleSearchSyntax2PgException('Точная поисковая фраза не содержит слов');
        }

        if (!preg_match_all('/[\*\s]+/i', $str, $matches)) {
            return $str;
        }

        foreach ($matches[0] as $match)
        {
            $len = strlen(str_replace(' ', '', $match)) + 1;
            $str = str_replace($match, "<$len>", $str);
        }

        return $str;
    }
}