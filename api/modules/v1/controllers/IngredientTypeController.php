<?php

namespace app\api\modules\v1\controllers;

class IngredientTypeController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\api\modules\v1\models\IngredientType';
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
