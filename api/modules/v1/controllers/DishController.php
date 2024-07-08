<?php

namespace app\api\modules\v1\controllers;

use app\api\modules\v1\models\Ingredient;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

class DishController extends Controller
{
    public $modelClass = 'app\api\modules\v1\models\Ingredient';

    /**
     * @param $code
     * @return Response
     */
    public function actionIndex($code)
    {
        $ingredientGroup = Ingredient::findDishByIngredientCode($code);
        $code = str_split($code, 1);
        $ingredientGroupNotFound = false;
        $ingredientGroupEmpty = false;
        $success =false;

        foreach ($code as $item) {
            $c = substr_count(implode($code),$item);
            $groups = Ingredient::getOccurenceOfCodeGroup();

            if ($groups && !isset($groups[$item])) {
                $ingredientGroupNotFound = true;
                break;
            }

            if (!$groups) {
                $ingredientGroupEmpty = true;
            }
        }

        if ($ingredientGroup && !$ingredientGroupNotFound) {
            foreach ($ingredientGroup as $ingredientGroupKey => $group) {
                $summary[$ingredientGroupKey]['products'] = null;
                $summary[$ingredientGroupKey]['price'] = 0;
                foreach ($group as $key => $match) {
                    $summary[$ingredientGroupKey]['products'][] = [
                        'type' => $match['type']['title'],
                        'value' => $match['title']
                    ];
                    $summary[$ingredientGroupKey]['price'] += (float) $match['price'];
                }
            }
        } else {
            Yii::$app->response->statusCode = 400;
            if ($ingredientGroupNotFound) {
                $message = ["Wrong input string format ! The Ingredients you entered doesn't exist"];
            } else if ($ingredientGroupEmpty) {
                $message = ["Database is empty"];
            }

            return $this->asJson([
                'code' => Yii::$app->response->statusCode,
                'message' => $message
            ]);
        }

        return $this->asJson($summary);
    }

    private function errorResponse($message)
    {
        // set response code to 400
        Yii::$app->response->statusCode = 400;

        return $this->asJson(['message' => $message]);
    }

}
