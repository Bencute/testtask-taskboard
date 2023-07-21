<?php

/**
 * @var \System\Kernel\View $this
 * @var \System\Kernel\WebUser $user
 */
?>
<div class="d-flex flex-column flex-sm-row align-items-center pt-0 pb-3 px-3 pt-sm-3 px-sm-4 mb-3 bg-white border-bottom shadow-sm">
    <a href="/" class="btn text-dark mr-sm-auto font-weight-normal">
        <h5 class="my-0">Задачник</h5>
    </a>
    <?php if ($user->isGuest()) { ?>
        <a class="btn btn-primary mr-sm-2 mb-2 mb-sm-0" href="/login">Войти</a>
    <?php } else { ?>
        <a class="btn btn-primary" data-method="post" href="/logout">Выход</a>
    <?php } ?>
</div>
