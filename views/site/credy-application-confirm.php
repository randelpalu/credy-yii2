<?php
/** @var string $response */
/** @var CredyApplicationForm $model */
/** @var string $timestamp */
/** @var string $signature */
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

use app\models\CredyApplicationForm;
use yii\bootstrap5\Html;

$this->title = 'Credy Application';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-credy-application-confirm">

    <div class="row">
        <div class="col-lg-8">
            <p><?= Html::encode($response) ?></p>

            <p>Following data was submitted:</p>

            <ul>
                <li><label>First name</label>: <?= Html::encode($model->firstName) ?></li>
                <li><label>Last name</label>: <?= Html::encode($model->lastName) ?></li>
                <li><label>Email</label>: <?= Html::encode($model->email) ?></li>
                <li><label>Bio</label>: <?= Html::encode($model->bio) ?></li>
                <li><label>Technologies</label>: <?= Html::encode($model->technologies) ?></li>
                <li><label>vcs uri</label>: <?= Html::encode($model->vcsUri) ?></li>
                <li><label>Timestamp</label>: <?= Html::encode($timestamp) ?></li>
                <li><label>Signature</label>: <?= Html::encode($signature) ?></li>
            </ul>
        </div>
    </div>
</div>
