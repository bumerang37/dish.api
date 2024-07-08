<?php

namespace app\api\modules\v1\controllers;

class IngredientController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\api\modules\v1\models\Ingredient';
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
