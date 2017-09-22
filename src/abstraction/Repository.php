<?php

namespace DangerousScheduler\abstraction;


interface Repository
{
    /**
     * Возврашает Объект задания
     *
     * @return Task
     */
    public function getTask();

    /**
     * Возвращает объект задания обратно в очередь
     *
     * @param Task $task
     * @return mixed
     */
    public function putTask($task);
}