<?php
/**
 * @var \System\Kernel\View $this
 * @var string $login
 */
?>
<div class="text-center">
    <h1>Вход</h1>

    <form method="post" action="/login" class="form-login js-needs-validation" novalidate>
        <div class="form-group">
            <label for="inputName" class="col-sm-4 col-form-label">Логин</label>
            <input name="login" type="text"
                   class="form-control"
                   id="inputName"
                   value="<?=$login?>"
                   required>
        </div>

        <div class="form-group">
            <label for="inputPassword" class="col-sm-4 col-form-label">Пароль</label>
            <input name="password" type="password"
                   class="form-control"
                   id="inputPassword" required>
        </div>

        <button type="submit" formnovalidate class="btn btn-primary btn-block">Войти</button>
    </form>
</div>
