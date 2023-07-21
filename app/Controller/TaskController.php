<?php

namespace App\Controller;

use App\Models\Task;
use App\Repository\Task\NotFound;
use App\Repository\Task\Repository;
use App\Form\Task as FormTask;
use App\Repository\Task\SaveError;
use App\Repository\Task\UpdateError;
use System\Db\DbException;
use System\Kernel\View;
use Throwable;
use System\Kernel\WebUser;
use System\Kernel\Controller;

class TaskController extends Controller
{
    const COUNT_TASKS_PER_PAGE = 3;
    const DEFAULT_SORT = 'nameAsk';

    private WebUser $user;
    private Repository $taskRepository;

    public function __construct()
    {
        $this->user = new WebUser;
        $this->taskRepository = new Repository;
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function index(): string
    {
        $isGuest = $this->user->isGuest();
        $page = (int) ($_GET['page'] ?? 1);
        $currentSort = (string) ($_GET['sort'] ?? self::DEFAULT_SORT);

        $tasks = $this->taskRepository->all(self::COUNT_TASKS_PER_PAGE, $page, $currentSort);

        $countPages = ceil($this->taskRepository->count() / self::COUNT_TASKS_PER_PAGE);

        return (new View('index', 'main'))->render([
            'isGuest' => $isGuest,
            'tasks'=> $tasks,
            'page' => $page,
            'currentSort' => $currentSort,
            'countPages' => $countPages,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function create(): string
    {
        $form = new FormTask();
        $data = $_POST;

        if (!empty($data)) {
            $form->name = $data['name'] ?? '';
            $form->email = $data['email'] ?? '';
            $form->content = $data['content'] ?? '';

            if ($form->validate()) {
                $task = new Task($form->name, $form->email, $form->content);
                try {
                    $this->taskRepository->save($task);
                    $this->user->addMessageSuccess('Задача успешно сохранена');
                    return $this->redirect('/');
                } catch (DbException | SaveError $e) {
                    $this->user->addMessageError('Не удалось сохранить задачу');
                }
            }
        }
        return (new View('addTask', 'main'))->render(['form' => $form]);
    }

    /**
     * @throws Throwable
     */
    public function update($id): string
    {
        if ($this->user->isGuest()) {
            $this->user->addMessageError('Для редактирования задачи нужно авторизоваться');
            return $this->redirect('/');
        }

        try {
            $task = $this->taskRepository->find($id);
        } catch (NotFound | DbException $e) {
            $this->user->addMessageError($e->getMessage());
            return $this->redirect('/');
        }

        $form = new FormTask();
        $form->name = $task->getName();
        $form->email = $task->getEmail();
        $form->content = $task->getContent();
        $form->done = $task->isDone();

        $data = $_POST;
        if (!empty($data)) {
            $form->name = $data['name'] ?? '';
            $form->email = $data['email'] ?? '';
            $form->content = $data['content'] ?? '';
            $form->done = isset($data['done']);

            if ($form->validate()) {
                if ($form->content !== $task->getContent()) {
                    $task->setIsUpdated(true);
                }

                $task->setName($form->name);
                $task->setEmail($form->email);
                $task->setContent($form->content);
                $task->setIsDone($form->done);

                try {
                    $this->taskRepository->update($task);
                    $this->user->addMessageSuccess('Задача успешно сохранена');
                    return $this->redirect('/');
                } catch (DbException $e) {
                    $this->user->addMessageError('Не удалось сохранить задачу');
                } catch (UpdateError $e) {
                    $this->user->addMessageError($e->getMessage());
                }
            }
        }

        return (new View('editTask', 'main'))->render([
            'form' => $form,
            'task' => $task
        ]);
    }
}