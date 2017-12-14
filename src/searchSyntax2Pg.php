<?php

namespace avtomon;

class searchSyntax2PgException extends \Exception
{
}

class searchSyntax2Pg
{
    /**
     * Возвращает поисковую фразу в формате корректном для добавления в запрос к БД PostgreSQL для поиск совпадений
     *
     * @param string $search - поисковая фраза
     * @param string $syntaxModel - формат входных данных
     *
     * @return string
     */
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

        return implode('', $result);
    }

    /**
     * Возвращает подстроку "точного совпадения" ("слово1 слово2..." или !слово) в Posgres-формате
     *
     * @param string $str подстрака точного совпадения
     *
     * @return string
     */
    public static function getExactFromGoogle(string $str): string
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