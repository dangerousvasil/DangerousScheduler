## Sheduler 

## Create Server 

set up you server by creating new settings class
```php
<?php

namespace examples\test1;

class Settings extends \DangerousScheduler\ServerSettings
{
    /**
     * Class for TaskRepository
     * @var string
     */
    public $repositoryClassName = Repository::class;

    public $treads = 10;
}
```

create youself you repository class

```php

<?php

namespace examples\test1;


use DangerousScheduler\Task;
use examples\test1\ExampleJob;

class Repository implements \DangerousScheduler\abstraction\Repository
{
    /**
     *
     *
     * @return Task
     */
    public function getTask() : Task
    {
        $db = static::$db;
        $data  = $db->getTaskFromQueue();
        
        $task = new Task();
        $task->name = $data['name'];// ExampleJob::class;
        $task->arguments = $data['arg'];
        
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
        $db = static::$db;
        $data  = $db->putTask2Queue();
        return true;
    }
}
```

Create your jobs and use repository method to put it in yours Queue.

In job you must implement method for validate and accept job and reject job 

```php
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

```

create run file Server.php
```php
use DangerousScheduler\Server;


$server = new Server(new Settings());
$server->run();


```

add to cron tab 
```
* * * * * flock -w 40 /var/run/myscript.lock 'php /path/to/Server.php'

```