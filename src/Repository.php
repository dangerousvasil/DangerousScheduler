<?php

namespace DangerousScheduler;


abstract class Repository implements abstraction\Repository
{
    /**
     * @inheritdoc
     *
     * @return Task
     * @throws \Exception
     */
    public function getTask()
    {
        /// Вы можете использовать своё хранилише очередей и наследоваться от абстрактного класса
        throw new \Exception('Need implements getTask Method');

    }

    /**
     * Добавляет объект задания обратно в очередь
     * @param $task
     * @return bool
     */
    public function putTask( $task)
    {
        /// Вы можете использовать своё хранилише очередей и наследоваться от абстрактного класса
        // TODO: Implement putTask() method.
        return true;
    }
}