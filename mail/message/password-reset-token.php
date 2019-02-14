<?php
use yii\helpers\Html;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['index/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <h3><?= $title?></h3>

    <p>你好 <?= Html::encode($user->username) ?>,</p>

    <p>点击以下链接 可完成密码重置:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>