<?php

use Mindy\Orm\Fields\FileField;
use Mindy\Orm\Model;

class StorageModel extends Model
{
    public static function getFields()
    {
        return [
            'file' => [
                'class' => FileField::className()
            ]
        ];
    }

    public static function getModuleName()
    {
        return 'StorageModel';
    }

    public static function tableName()
    {
        return 'test';
    }
}
