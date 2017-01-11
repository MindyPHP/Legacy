# Tables

Репозиторий является клоном `django-tables2` на php.

## Пример использования

```php
<?php

namespace Modules\Example\Tables;

use Mindy\Table\Columns\RawColumn;
use Mindy\Table\Table;
use Modules\Example\ExampleModule;

class ExampleDataTable extends Table
{
    public function getColumns()
    {
        return [
            'created_at' => [
                'class' => RawColumn::className(),
                'title' => CoreModule::t('Created at')
            ],
            'message' => [
                'class' => RawColumn::className(),
                'title' => CoreModule::t('Message')
            ],
            'ip' => [
                'class' => RawColumn::className(),
                'title' => CoreModule::t('Ip')
            ],
            'username',
            'url'
        ];
    }
}

```

Использование в контроллере:

```php
...
public function actionIndex()
{
    $qs = ExampleData::objects();
    $table = new ExampleDataTable($qs, [
        'paginationConfig' => [
            'pageSize' => 20
        ]
    ]);
    echo $this->render('example/index.html', [
        'table' => $table
    ]);
}
...

Шаблон:

```twig
{{ table|safe }} или {{ table.render()|safe }}
```

## Столбцы (Columns)

В текущей версии реализовано только 3 класса `Column`:

* `TemplateColumn` - Пример использования:

```php
...
    'foo' => [
        'class' => '\Mindy\Table\Columns\TemplateColumn',
        'template' => 'my_app/my_template.html',
        'title' => 'Super title'
    ]
...
```

Аргументы передаваемые в шаблон:

```php
...
    'value' => $value, // Значение
    'record' => $record, // Модель
    'table' => $table // Таблица
...
```

* `NumberColumn` - Пример использования:

```php
...
    'foo' => [
        'class' => '\Mindy\Table\Columns\NumberColumn',
        'decimals' => 2,
        'decPoint' => '.',
        'thousandsSep' => ',',
        'title' => 'Super title'
    ]
...
```

* `RawColumn` - Пример использования:

```php
...
    'foo' => [
        'class' => '\Mindy\Table\Columns\RawColumn',
        'title' => 'Super title'
    ]
...
```
