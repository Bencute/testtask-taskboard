<?php
/**
 * @var \System\Kernel\View $this
 * @var \System\Kernel\WebUser $user
 */
?>
<?php foreach ($user->getMessagesSuccess() as $msg) { ?>
    <div class="alert alert-success" role="alert">
        <?=$msg?>
    </div>
<?php } ?>

<?php foreach ($user->getMessagesError() as $msg) { ?>
    <div class="alert alert-danger" role="alert">
        <?=$msg?>
    </div>
<?php } ?>
