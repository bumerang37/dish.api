<?php

/** @var yii\web\View $this */

use app\api\modules\v1\models\Ingredient;
use app\api\modules\v1\models\IngredientType;

$this->title = 'Dish';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Congratulations!</h1>
        <div class="row">
            <div class="">
                <p class="lead">
                <p>Quick start:</p>
                <ol class="text-center">
                    <li class="instruct display-6" style="width: 50%; margin: 0 auto;">
                        <code><samp>php yii migrate</samp></code> - From <code><samp>Workdir</samp></code>
                    </li>
                </ol>
                </p>

                <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['v1/dish', 'code' => 'dcii']) ?>">Get
                        started with Dishes constructor</a></p>
            </div>
        </div>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Ingredients</h2>

                <p><?= Ingredient::listIngredients(Ingredient::getIngredients()) ?></p>

                <p><a class="btn btn-outline-secondary" href="<?= \yii\helpers\Url::to(['v1/ingredients']) ?>">Ingredients</a>
                </p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2>Ingredient Types</h2>

                <p><?= IngredientType::listIngredientTypes(IngredientType::getIngredientTypes()) ?></p>

                <p><a class="btn btn-outline-secondary" href="<?= \yii\helpers\Url::to(['v1/ingredient-types']) ?>">Ingredient
                        Types</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Dishes constructor</h2>
                <p>Find Dish by ingredient template.
                <p>Single char of <code><samp>{code}</samp></code> string represents Ingredient Group code
                    from <b>Ingredient Types</b></p>
                <p>GET /v1/dish/<code><samp>{code}</samp></code></p>
                <p><code><samp>{code}</samp></code> - string </p>

                <p><a class="btn btn-outline-secondary"
                      href="<?= \yii\helpers\Url::to(['v1/dish', 'code' => 'dcii']) ?>">Dishes constructor</a></p>
            </div>
        </div>

    </div>
</div>
