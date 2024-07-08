<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ingredient}}`.
 */
class m240705_044509_create_ingredient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ingredient}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer(),
            'title' => $this->string(255)->comment('название ингредиента'),
            'price' => $this->decimal(19, 2)
        ]);

        $this->createIndex('idx-ingredient-id', '{{%ingredient}}', 'id');

        $this->addForeignKey(
            'FK_' . 'ingredient' . '_ingredient_type_id',
            '{{%ingredient}}',
            'type_id',
            '{{%ingredient_type}}',
            'id'
        );

        $this->batchInsert(
            '{{%ingredient}}',
            ['id', 'type_id', 'title', 'price'],
            [
                [
                    'id' => 1,
                    'type_id' => 1,
                    'title' => 'Тонкое тесто',
                    'price' => 100.00,
                ],
                [
                    'id' => 2,
                    'type_id' => 1,
                    'title' => 'Пышное тесто',
                    'price' => 110.00,
                ],
                [
                    'id' => 3,
                    'type_id' => 1,
                    'title' => 'Ржаное тесто',
                    'price' => 150.00,
                ],
                [
                    'id' => 4,
                    'type_id' => 2,
                    'title' => 'Моцарелла',
                    'price' => 50.00,
                ],
                [
                    'id' => 5,
                    'type_id' => 2,
                    'title' => 'Рикотта',
                    'price' => 70.00,
                ],
                [
                    'id' => 6,
                    'type_id' => 3,
                    'title' => 'Колбаса',
                    'price' => 30.00,
                ],
                [
                    'id' => 7,
                    'type_id' => 3,
                    'title' => 'Ветчина',
                    'price' => 35.00,
                ],
                [
                    'id' => 8,
                    'type_id' => 3,
                    'title' => 'Грибы',
                    'price' => 50.00,
                ],
                [
                    'id' => 9,
                    'type_id' => 3,
                    'title' => 'Томаты',
                    'price' => 10.00,
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'FK_' . 'ingredient' . '_ingredient_type_id',
            '{{%ingredient}}'
        );
        $this->dropTable('{{%ingredient}}');
    }
}
