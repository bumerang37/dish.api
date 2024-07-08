<?php

namespace app\api\modules\v1\models;

use app\api\modules\v1\models\Ingredient;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "ingredient_type".
 *
 * @property int $id
 * @property string|null $title название типа ингредиента
 * @property string|null $code 1-буквенный код ингредиента
 *
 * @property Ingredient[] $ingredients
 */
class IngredientType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'code' => 'Code',
        ];
    }

    /**
     * Gets query for [[Ingredients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngredients()
    {
        return $this->hasMany(Ingredient::class, ['type_id' => 'id']);
    }

    public static function getIngredientTypes()
    {
        $q = IngredientType::find()->select('id, title, code')->all();
        if ($q) {
            return $q;
        }
        return;
    }

    public static function listIngredientTypes($ingredientTypes)
    {
        if ($ingredientTypes) {
            return Html::ul(ArrayHelper::map($ingredientTypes, 'id', function ($ingredientType) {
                return $ingredientType->id . ": " . $ingredientType->title . ' - ' .$ingredientType->code;
            }),
                ['class' => 'list-group d-flex flex-row flex-wrap test', 'itemOptions' => ['class' => 'item-list w-50']]);
        }
    }

}
