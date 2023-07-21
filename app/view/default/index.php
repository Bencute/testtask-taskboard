<?php

use App\Helper\Html;
use App\Helper\HtmlPagination;

/**
 * @var \System\Kernel\View $this
 * @var bool $isGuest
 * @var int $page
 * @var string $currentSort
 * @var \App\Models\Task[] $tasks
 * @var int $countPages
 */
?>
<div class="text-center">
    <p>
        <a href="/create" class="btn btn-primary btn-lg">Добавить задачу</a>
    </p>
</div>


<?php if ($tasks) { ?>
    <?=$this->renderView('_sortSelect', ['currentSort' => $currentSort])?>

    <?php foreach ($tasks as $task) { ?>

        <div class="card my-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-1">
                        #<?=$task->getId()?>
                    </div>
                    <div class="col">
                        <?=$task->getName()?> <span class="text-black-50"><?=$task->getEmail()?></span>
                        <?php if (!$isGuest) { ?>
                            <a class="btn btn-primary btn-sm" href="/update/<?=$task->getId()?>">Изменить</a>
                        <?php } ?>
                    </div>
                    <?php if ($task->isDone() || $task->isUpdated()) { ?>
                        <div class="col-auto">
                            <?php if (!$isGuest && $task->isUpdated()) { ?>
                                <span class="badge badge-warning">отредактировано администратором</span>
                            <?php } ?>
                            <?php if ($task->isDone()) { ?>
                                <span class="badge badge-success">завершено</span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <?=Html::encode($task->getContent())?>
                </p>
            </div>
        </div>
    <?php } ?>

    <?php if ($countPages > 1) { ?>
        <nav aria-label="Page navigation example" class="my-3">
            <ul class="pagination justify-content-center">
                <?php for ($i = 0; $i < $countPages; $i++) { ?>
                    <li class="page-item">
                        <a class="page-link" href="<?=HtmlPagination::getLink($i + 1)?>">
                            <?=$i + 1?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    <?php } ?>
<?php } else { ?>
    <div>Нет созданных задач</div>
<?php } ?>
