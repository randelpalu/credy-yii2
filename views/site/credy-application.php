<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var CredyApplicationForm $model */

use app\models\CredyApplicationForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Credy Application';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-credy-application">

    <div class="row">
        <div class="col-lg-8">

            <?php $form = ActiveForm::begin(['id' => 'credy-application-form']); ?>

            <?= $form->field($model, 'firstName') ?>
            <?= $form->field($model, 'lastName') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'bio')->textarea(['rows' => '10']) ?>
            <?= $form->field($model, 'technologies')->textarea(['rows' => '2'])->label('Technologies (comma separated values please)') ?>
            <?= $form->field($model, 'vcsUri') ?>

            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
