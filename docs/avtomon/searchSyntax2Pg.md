<small>avtomon</small>

searchSyntax2Pg
===============

Полнотекстовый поиск в PostgreSQL c поддержкой поискового синтаксиса Google и Yandex

Описание
-----------

Class searchSyntax2Pg

Сигнатура
---------

- **class**.

Методы
-------

Методы класса class:

- [`getPgSyntax()`](#getPgSyntax) &mdash; Возвращает поисковую фразу в формате корректном для добавления в запрос к БД PostgreSQL для поиск совпадений
- [`getExact()`](#getExact) &mdash; Возвращает подстроку &quot;точного совпадения&quot; (&quot;слово1 слово2.

### `getPgSyntax()` <a name="getPgSyntax"></a>

Возвращает поисковую фразу в формате корректном для добавления в запрос к БД PostgreSQL для поиск совпадений

#### Сигнатура

- **public static** method.
- Может принимать следующий параметр(ы):
    - `$search` (`string`) &mdash; - поисковая фраза
    - `$syntaxModel` (`string`) &mdash; - формат входных данных
    - `$config` (`string`) &mdash; - язык
- Может возвращать одно из следующих значений:
    - `null`
    - `string`
- Выбрасывает одно из следующих исключений:
    - [`avtomon\searchSyntax2PgException`](../avtomon/searchSyntax2PgException.md)

### `getExact()` <a name="getExact"></a>

Возвращает подстроку "точного совпадения" ("слово1 слово2.

#### Описание

.." или !слово) в Posgres-формате

#### Сигнатура

- **public static** method.
- Может принимать следующий параметр(ы):
    - `$str` (`string`) &mdash; подстрака точного совпадения
- Возвращает `string` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\searchSyntax2PgException`](../avtomon/searchSyntax2PgException.md)

