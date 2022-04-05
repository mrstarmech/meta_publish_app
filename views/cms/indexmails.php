<?php

use yii\bootstrap4\Button;
use yii\helpers\Url;
use app\models\UserMail;

$this->title = 'Mail Log';
?>
<div class="site-index" style="padding: 0 15px;">
    <table class="table table-sm table-bordered table-hover" style="font-size: 12px; min-width: 1920px">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Time</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($mails as $mail): ?>
                <tr>
                    <td><?=$mail->id?></th>
                    <td><?=$mail->name?></td>
                    <td><?=$mail->email?></td>
                    <td><?=$mail->time?></td>
                    <td><?=$mail->time?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>