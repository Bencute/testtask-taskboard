<?php

namespace App\Repository\Task;

use App\Models\Task;
use PDO;
use System\Db\MysqlQuery;

class Repository
{
    const DEFAULT_SORT = 'nameAsk';
    private MysqlQuery $provider;

    public function __construct()
    {
        $this->provider = new MysqlQuery;
    }

    private function getSortParams(): array
    {
        return [
            'nameAsk' => [
                'attribute' => 'name',
                'order' => 'asc',
            ],
            'nameDesc' => [
                'attribute' => 'name',
                'order' => 'desc',
            ],
            'emailAsk' => [
                'attribute' => 'email',
                'order' => 'asc',
            ],
            'emailDesc' => [
                'attribute' => 'email',
                'order' => 'desc',
            ],
            'doneAsk' => [
                'attribute' => 'done',
                'order' => 'asc',
            ],
            'doneDesc' => [
                'attribute' => 'done',
                'order' => 'desc',
            ],
        ];
    }

    private function tableName(): string
    {
        return 'tasks';
    }

    /**
     * @return Task[]
     * @throws \Exception
     */
    public function all(int $limit, int $page, string $sort): array
    {
        $condition['limit']['offset'] = ($page - 1) * $limit;
        $condition['limit']['count'] = $limit;

        $sortParams = $this->getSortParams();
        $sortParam = $sortParams[self::DEFAULT_SORT];
        if (isset($sortParams[$sort])) {
            $sortParam = $sortParams[$sort];
        }
        $condition['order'][$sortParam['attribute']] = $sortParam['order'];

        $resultItems = $this->provider->select($this->tableName(), $condition)->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($resultItems as $item) {
            $task = new Task(
                $item['name'],
                $item['email'],
                $item['content'],
                (bool) $item['done'],
                (bool) $item['updated'],
                (int) $item['id']);

            $tasks[] = $task;
        }

        return $tasks;
    }

    /**
     * @throws \System\Db\DbException
     * @throws SaveError
     */
    public function save(Task $task): Task
    {
        $dataForInsert = [
            'name' => $task->getName(),
            'email' => $task->getEmail(),
            'content' => $task->getContent(),
            'done' => (int) $task->isDone(),
            'updated' => (int) $task->isUpdated(),
        ];

        if (!$this->provider->insert($this->tableName(), $dataForInsert)) {
            throw new SaveError('Не удалось сохранить задачу');
        }
        $task->setId($this->provider->getConnect()->lastInsertId());

        return $task;
    }

    /**
     * @throws NotFound
     * @throws \System\Db\DbException
     */
    public function find(int $id): Task
    {
        $condition['columns']['id'] = $id;
        $condition['limit']['count'] = 1;

        $resultItems = $this->provider->select(static::tableName(), $condition)->fetchAll(PDO::FETCH_ASSOC);

        if (!count($resultItems)) {
            throw new NotFound('Задача не найдена');
        }

        $item = $resultItems[0];
        return new Task(
            $item['name'],
            $item['email'],
            $item['content'],
            (bool) $item['done'],
            (bool) $item['updated'],
            (int) $item['id']);
    }

    /**
     * @throws \System\Db\DbException
     * @throws UpdateError
     */
    public function update(Task $task): void
    {
        $dataForUpdate = [
            'id' => $task->getId(),
            'name' => $task->getName(),
            'email' => $task->getEmail(),
            'content' => $task->getContent(),
            'done' => (int) $task->isDone(),
            'updated' => (int) $task->isUpdated(),
        ];

        if (!$this->provider->update(static::tableName(), 'id', $dataForUpdate)) {
            throw new UpdateError('Не удалось обновить задачу');
        }
    }

    /**
     * @throws \System\Db\DbException
     */
    public function count(array $params = []): int
    {
        $result = $this->provider->count(static::tableName(), $params);
        if ($result === false)
            return 0;

        return (int) $result->fetchColumn();
    }
}