<?php

namespace examples\test1;

require_once '../../vendor/autoload.php';
require_once 'Settings.php';
require_once 'Repository.php';
require_once 'ExampleJob.php';

use DangerousScheduler\Server;


$server = new Server(new Settings());
$server->run();