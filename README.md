[![Latest Stable Version](https://poser.pugx.org/arrilot/bitrix-blade/v/stable.svg)](https://packagist.org/packages/arrilot/bitrix-blade/)
[![Total Downloads](https://img.shields.io/packagist/dt/arrilot/bitrix-blade.svg?style=flat)](https://packagist.org/packages/Arrilot/bitrix-blade)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/arrilot/bitrix-blade/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arrilot/bitrix-blade/)

# Данный пакет больше активно не поддерживается

Причина - мы больше не используем Битрикс в своих проектах.
Если вам интересен этот проект и вы хотите заняться его поддержкой - форкните его и создайте Issue в данном репозитории чтобы мы поместили здесь ссылку на форк.

# Bitrix Blade - интеграция шаблонизатора Blade в Битрикс

## Установка

1)```composer require arrilot/bitrix-blade```

2) добавляем в init.php

```php

use Arrilot\BitrixBlade\BladeProvider;

require $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";

BladeProvider::register();
```

## Использование

Заменяем шаблон компонента с `template.php` на `template.blade` и можно писать на `Blade`

Директива `@include('path.to.view')` модифицирована следующим образом:

1. Сначала view ищется относительно директории текущего шаблона компонента (там где лежит template.blade)
2. Если не view там не нашёлся, то он ищется относительно базовой директории (по умолчанию `local/views`, но может быть указана другая при вызове `BladeProvider::register()`)

## Пользовательские директивы (custom directives)

Для того чтобы добавить свою директиву, необходимо зарегистрировать её в компиляторе:

```
$compiler = BladeProvider::getCompiler();
$compiler->directive('directiveName', function ($expression) {
    return '...';
});
```
При установке пакета `BladeProvider::register()` за вас уже автоматически зарегистрировано некоторое количество полезных директив:

1. ```@bxComponent```  - аналог ```$APPLICATION->IncludeComponent()```
2. ```@block('key')``` и ```@endblock``` - всё что заключено между ними будет выведено в месте, где вызван метод ```$APPLICATION->ShowViewContent('key')```
3. ```@lang('key')``` - равносильно ```{!! Bitrix\Main\Localization\Loc::getMessage('key') !!} ```
4. ```@auth``` и ```@endauth``` - сокращенная запись `<?php if($USER->IsAuthorized()) ?> ... <?php endif ?>`
5. ```@guest``` и ```@endguest``` - аналогично, но проверка на неавторизованного юзера.
6. ```@admin``` и ```@endadmin``` - аналогично, но `$USER->IsAdmin()`
7. ```@csrf``` - сокращенная форма для ```<input type="hidden" name="sessid" value="{!! bitrix_sessid() !!}" />```
8. [Директивы по работе с эрмитажем](docs/hermitage.md)

## Конфигурация

При необходимости пути можно поменять в конфигурации.
.settings_extra.php
```php
    'bitrix-blade' => [
        'value'    => [
            'baseViewPath' => '/absolute/path/or/path/from/document/root', // по умолчанию 'local/views'
            'cachePath' => '/absolute/path/or/path/from/document/root', // по умолчанию 'local/cache/blade'
        ],
        'readonly' => false,
    ],
```

## Очистка кэша

Для обеспечения высокой скорости работы Blade кэширует скомпилированные шаблоны в php файлы.
В большинстве случаев чистить этот кэш самостоятельно потребности нет, потому что блейд сверяет время модификации файлов шаблонов и кэша и самостоятеьно инвалидирует этот кэш.
Однако в некоторых случаях (например при добавлении новой пользовательской директивы), этот кэш всё-же надо сбросить.
Делается это методом ```BladeProvider::clearCache()```


## Некоторые моменты

1. Битрикс позволяет использовать сторонние шаблонизаторы только в шаблонах компонентов. Шаблоны сайтов только на php.
2. По понятным причинам наследованием шаблонов в полную силу воспользоваться не получится.
3. Традиционное расширение `.blade.php` использовать нельзя. Битрикс видя `.php` включает php движок.
4. Вместо `$this` в шаблоне следует использовать `$template` - например `$template->setFrameMode(true);`
5. Проверку `<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true) die();?>` прописывать в blade-шаблоне не нужно, она добавляется в скомпилированные view автоматически. Также вместе с этим выполняется и ```extract($arResult, EXTR_SKIP);```
6. Чтобы языковой файл из шаблона подключился, его (этот языковой файл) надо назвать как обычно - `template.php`

## Дополнительно

PhpStorm

1. Чтобы включить подсветку синтаксиса в PhpStorm для .blade файлов нужно добавить это расширение в
`Settings->Editor->File Types->Blade`
2. Чтобы PhpStorm понимал и подсвечивалл должным образом пользовательские директивы из этого пакета их можно добавить в него. Делается это в `Settings->Language & Frameworks->PHP->Blade->Directives`
