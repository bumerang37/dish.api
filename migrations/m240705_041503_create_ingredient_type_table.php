<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%ingredient_type}}`.
 */
class m240705_041503_create_ingredient_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ingredient_type}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->comment('название типа ингредиента'),
            'code' => $this->char()->comment('1-буквенный код ингредиента')
        ]);

        $this->createIndex('idx-ingredient_type-id', '{{%ingredient_type}}', 'id');

        $this->batchInsert(
            '{{%ingredient_type}}',
            ['id', 'title', 'code'],
            [
                [
                    'id' => 1,
                    'title' => 'Тесто',
                    'code' => 'd',
                ],
                [
                    'id' => 2,
                    'title' => 'Сыр',
                    'code' => 'c',
                ],
                [
                    'id' => 3,
                    'title' => 'Начинка',
                    'code' => 'i',
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ingredient_type}}');
    }
}
