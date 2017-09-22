<?php

namespace examples\test1;

class ExampleJob extends \DangerousScheduler\Job
{

    public function validateJob() : bool
    {
        echo 'TODO: Implement validateJob() method.' . PHP_EOL;

        return rand(1, 3) < 2;
    }

    public function acceptJob()
    {

        echo 'TODO: Implement acceptJob() method.' . PHP_EOL;

        return true;
    }

    public function rejectJob()
    {
        echo 'TODO: Implement rejectJob() method.' . PHP_EOL;
        // return task to order to redoing
        (new Repository())->putTask($this->task);

        return true;
    }

    public function doJob()
    {
        echo 'TODO: Implement doJob() method.' . PHP_EOL;
        sleep((int)rand(1, 15));
    }
}