<?php

namespace examples\test1;


use DangerousScheduler\Task;

class Repository implements \DangerousScheduler\abstraction\Repository
{
    public function __construct()
    {
        $conf = parse_ini_file('dbconf.ini', true);
        \dibi::connect($conf['dbConf']);

    }

    /**
     * Возврашает Обьект задания
     *
     * @return Task|null
     * @throws \Dibi\Exception
     */
    public function getTask()
    {
        $md5 = md5(random_bytes(32));
        \dibi::query('UPDATE task SET hash = CONCAT(' . time() . ', sha1("' . $md5 . '")) WHERE hash="" LIMIT 1');
        $taskRow = \dibi::query('SELECT * FROM task WHERE hash = CONCAT(' . time() . ', sha1("' . $md5 . '")) LIMIT 1')->fetchAll();
        if(!$taskRow){
            return null;
        }
        $taskData = json_decode($taskRow[0]->task,1);
        $task = new Task();
        $task->id = $taskRow[0]->id;
        $task->name = $taskData['name'];
        $task->arguments = $taskData['arg'];
        return $task;
    }


    /**
     * Возвращает объект задания обратно в очередь
     *
     * @param \DangerousScheduler\abstraction\Task $task
     * @return mixed
     * @throws \Dibi\Exception
     */
    public function putTask($task)
    {
        return \dibi::insert('task',
            ['task' => json_encode([
                'name' => $task->getName(),
                'arg'  => $task->getArguments(),
            ])]
        )->execute();
    }
}