<?php

namespace examples\test1;

require_once '../../vendor/autoload.php';
require_once 'Settings.php';
require_once 'Repository.php';
require_once 'ExampleJob.php';

use DangerousScheduler\Server;
use DangerousScheduler\Task;
use dibi;
// add test data
$repository = new Repository();
for ($i = 1; $i <= 3; $i++) {
    $task = new Task();
    $task->name = ExampleJob::class;
    $task->arguments = ['i' => $i];
    $repository->putTask($task);
}

// run server
$server = new Server(new Settings());
$server->run();