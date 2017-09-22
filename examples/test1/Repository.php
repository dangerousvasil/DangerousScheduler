<?php

namespace examples\test1;


use DangerousScheduler\Task;
use examples\test1\ExampleJob;

class Repository implements \DangerousScheduler\abstraction\Repository
{
    /**
     * Возврашает Обьект задания
     *
     * @return Task
     */
    public function getTask() : Task
    {
        $task = new Task();
        $task->name = ExampleJob::class;
        $task->arguments = [
            'arg1' => 1,
            'arg2' => 2,
        ];

        return $task;
    }


    /**
     * Возвращает объект задания обратно в очередь
     *
     * @param \DangerousScheduler\abstraction\Task $task
     * @return mixed
     */
    public function putTask($task)
    {
        return true;
    }
}