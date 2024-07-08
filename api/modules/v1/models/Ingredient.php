<?php

namespace app\api\modules\v1\models;


use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "ingredient".
 *
 * @property int $id
 * @property int|null $type_id
 * @property string|null $title название ингредиента
 * @property float|null $price
 *
 * @property IngredientType $type
 */
class Ingredient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id'], 'integer'],
            [['price'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => IngredientType::class, 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'title' => 'Title',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(IngredientType::class, ['id' => 'type_id']);
    }

    public static function getIngredients()
    {
        $q = Ingredient::find()->select('id, title')->all();
        if ($q) {
            return $q;
        }
        return;
    }

    public static function listIngredients($ingredients)
    {
        return Html::ul(ArrayHelper::map($ingredients, 'id', function($ingredient) {
         return $ingredient->id.": ".$ingredient->title;
        }),
            ['class' => 'list-group d-flex flex-row flex-wrap test', 'itemOptions' => ['class' => 'item-list w-50']]);
    }


    /**
     * Find groups of dish ingredient group by code of ingredient type code
     * Example dcii
     * @param $code
     * @return Ingredient|array|ActiveRecord
     */
    public static function findDishByIngredientCode($code)
    {
        //dcii
        //1234

        $array = [
            //d    '1' => 3,
            //c    '2' => 2,
            //i    '3' => 4
        ];
        $groups = [];
        $group = [];
        $exclude_values = [];

        $code = str_split($code, 1);
        $countIngredientByTypeId = self::getOccurenceOfCodeGroup();
        $possibleCombinationsArray = self::countPossibleNumberOfIngredientCombinations($code, $countIngredientByTypeId);


        for ($i = 0, $iMax = count($possibleCombinationsArray); $i < $iMax; $i++) {
            foreach ($code as $substr) {
                $ingredient = Ingredient::find()
                    ->select(['ingredient.id', 'ingredient.type_id', 'ingredient.title', 'ingredient.price'])
                    ->joinWith('type')
                    ->andWhere(['like', 'ingredient_type.code', $substr])
                    ->andWhere(['NOT IN', 'ingredient.id', $exclude_values])
                    ->orderBy(new Expression('rand()'))
//                    ->createCommand()->getRawSql();
                    ->one();
                if ($ingredient) {
                    $group[] = $ingredient;
                    $exclude_values[] = $ingredient['id'];
                    $countIngredientByTypeId[$substr]--;
                }

            }
            $groups[$i] = $group;
            if (count($exclude_values) === strlen(implode($code))) {
                $k = array_search($exclude_values, $possibleCombinationsArray);
                if (empty($k)) {
                    $k = array_search(sort($exclude_values), $possibleCombinationsArray);
                }
                unset($possibleCombinationsArray[$k]);
                $exclude_values = [];
                $group = [];
            }
        }

        return $groups;
    }

    public static function getOccurenceOfCodeGroup()
    {
        $q = (new Query())
            ->select('code, COUNT(type_id) as count')
            ->leftJoin('ingredient_type', 'ingredient.type_id = ingredient_type.id')
            ->from('ingredient')
            ->groupBy('type_id')
            ->all();

        return ArrayHelper::map($q, 'code', 'count');
    }

    public static function countPossibleNumberOfIngredientCombinations($code, $groups)
    {
        $groupsByTypeId = [];
        $ingredientsByGroup = (new Query())
            ->select('ingredient.id, code')
            ->leftJoin('ingredient_type', 'ingredient.type_id = ingredient_type.id')
            ->from('ingredient')
            ->groupBy('type_id,id')
            ->all();

        foreach ($ingredientsByGroup as $el) {
            $groupsByTypeId[$el['code']][] = (int)$el['id'];
        }
        return self::generateCombinations($groupsByTypeId, implode($code));
    }

    public static function generateCombinations($groups, $template)
    {
        function generateCombinationsForTemplate($groups, $template)
        {
            $inputCodes = str_split($template);
            $combinations = [];

            combine($groups, $inputCodes, 0, [], $combinations);
            return $combinations;
        }

        function combine($groups, $codes, $index, $currentCombination, &$combinations)
        {
            if ($index === count($codes)) {
                $combinations[] = $currentCombination;
                return;
            }

            $code = $codes[$index];
            $temp = [];

            if (isset($groups[$code])) {
                foreach ($groups[$code] as $element) {
                    if ((count($currentCombination) + 1) == strlen(implode($codes))) {
                        $temp = $currentCombination;
                        $temp[] = $element;
                        sort($temp);
                    }

                    //If sorted temp combination exist in combination array,
                    // current combination will be excluded
                    if (!in_array($element, $currentCombination) && !in_array($temp, $combinations)) {
                        $newCombination = $currentCombination;
                        $newCombination[] = $element;
                        combine($groups, $codes, $index + 1, $newCombination, $combinations);
                    }
                }
            }
        }

        return generateCombinationsForTemplate($groups, $template);

    }
}
