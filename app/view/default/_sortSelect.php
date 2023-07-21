<?php

/**
 * @var \System\Kernel\View $this
 * @var string $currentSort
 */
?>

<form class="form-inline" method="get" action="/">
    <div class="form-group">
        <label for="exampleFormControlSelect1" class="mr-2">Сортировка:</label>
        <select name="sort" class="form-control" id="exampleFormControlSelect1">
            <option <?=$currentSort=='nameAsk'?'selected':''?> value="nameAsk">По имени</option>
            <option <?=$currentSort=='nameDesc'?'selected':''?> value="nameDesc">По имени в обраном порядке</option>
            <option <?=$currentSort=='emailAsk'?'selected':''?> value="emailAsk">По email</option>
            <option <?=$currentSort=='emailDesc'?'selected':''?> value="emailDesc">По email в обраном порядке</option>
            <option <?=$currentSort=='doneAsk'?'selected':''?> value="doneAsk">По статусу</option>
            <option <?=$currentSort=='doneDesc'?'selected':''?> value="doneDesc">По статусу в обраном порядке</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary ml-2">Применить</button>
</form>