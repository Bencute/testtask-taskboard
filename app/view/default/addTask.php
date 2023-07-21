<?php

use App\Helper\Html;

/**
 * @var \System\Kernel\View $this
 * @var \App\Form\Task $form
 */
?>
<div>
    <h1 class="text-center mb-3">
        Создание задачи
    </h1>

    <p class="lead text-center">Заполните поля ниже</p>

    <form action="/create" method="post"
          class="form-registration px-3" novalidate>

        <div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">Имя</label>
            <div class="col-sm-8 px-0">
                <input name="name"
                       value="<?=$form->name?>"
                       type="text"
                       class="form-control
<?=Html::stateInValidClass($form->isAttributeError('name'))?>"
                       id="inputName" required>

                <?=Html::getMessageErrors($form->getAttributeMessageErrors('name'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
            <div class="col-sm-8 px-0">
                <input name="email"
                       value="<?=$form->email?>"
                       type="email"
                       class="form-control
                       <?=Html::stateInValidClass($form->isAttributeError('email'))?>"
                       id="inputEmail" required>

                <?=Html::getMessageErrors($form->getAttributeMessageErrors('email'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputDesc" class="col-sm-4 col-form-label">
                Описание задачи
            </label>
            <div class="col-sm-8 px-0">
                <textarea name="content"
                          rows="3"
                          class="form-control
                          <?=Html::stateInValidClass($form->isAttributeError('content'))?>"
                          id="inputDesc"><?=$form->content?></textarea>

                <?=Html::getMessageErrors($form->getAttributeMessageErrors('content'))?>
            </div>
        </div>

        <div class="form-group row  justify-content-center">
            <button type="submit" formnovalidate class="btn btn-primary btn-block col-sm-6">Создать</button>
        </div>
    </form>
</div>
