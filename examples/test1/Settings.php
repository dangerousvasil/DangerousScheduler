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

    public $log = true;
}