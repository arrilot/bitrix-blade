#Директивы по работе с эрмитажем

Используется вспомогательный пакет [https://github.com/arrilot/bitrix-hermitage](https://github.com/arrilot/bitrix-hermitage)

Таблица соответвтия директив и методов пакета

```php
@actionEditAndDeleteIBlockElement($element)             => Action::editAndDeleteIBlockElement($template, $element),
@actionEditIBlockElement($element)                      => Action::editIBlockElement($template, $element),
@actionDeleteIBlockElement($element, $confirm = '...')  => Action::deleteIBlockElement($template, $element, $confirm = '...'),

@actionEditAndDeleteIBlockSection($section)             => Action::editAndDeleteIBlockSection($template, $section),
@actionEditIBlockSection($section)                      => Action::editIBlockSection($template, $section),
@actionDeleteIBlockSection($section, $confirm = '...')  => Action::deleteIBlockSection($template, $section, $confirm = '...'),

@actionEditAndDeleteHLBlockElement($element)            => Action::editAndDeleteHLBlockElement($template, $element) ,
@actionEditHLBlockElement($element)                     => Action::editHLBlockElement($template, $element),
@actionDeleteHLBlockElement($element, $confirm = '...') => Action::deleteHLBlockElement($template, $element, $confirm = '...'),

@actionAddForIBlock($iblockId, [...])                   => Action::addForIBlock($templateOrComponent, $iblockId, [...]),
```
