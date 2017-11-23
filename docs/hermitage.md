#Директивы по работе с эрмитажем

Используется вспомогательный пакет [https://github.com/arrilot/bitrix-hermitage](https://github.com/arrilot/bitrix-hermitage)

Таблица соответвтия директив и методов пакета

```php
@actionEditIBlockElement($element)                      => Action::editIBlockElement($template, $element),
@actionDeleteIBlockElement($element, $confirm = '...')  => Action::deleteIBlockElement($template, $element, $confirm = '...'),
@actionEditAndDeleteIBlockElement($element)             => Action::editAndDeleteIBlockElement($template, $element),

@actionEditIBlockSection($section)                      => Action::editIBlockSection($template, $section),
@actionDeleteIBlockSection($section, $confirm = '...')  => Action::deleteIBlockSection($template, $section, $confirm = '...'),
@actionEditAndDeleteIBlockSection($section)             => Action::editAndDeleteIBlockSection($template, $section),

@actionEditHLBlockElement($element)                     => Action::editHLBlockElement($template, $element),
@actionDeleteHLBlockElement($element, $confirm = '...') => Action::deleteHLBlockElement$template, $element, $confirm = '...'),
@actionEditAndDeleteHLBlockElement($element)            => Action::editAndDeleteHLBlockElement($template, $element) ,

@actionAddForIBlock($iblockId)                          => Action::addForIBlock($templateOrComponent, $iblockId, [...]),
```