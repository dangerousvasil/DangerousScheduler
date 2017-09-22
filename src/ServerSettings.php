<?php
namespace DangerousScheduler;


use DangerousScheduler\abstraction\Repository;

class ServerSettings implements \DangerousScheduler\abstraction\ServerSettings
{
    /**
     * Class for TaskRepository
     * @var string
     */
    public $repositoryClassName = Repository::class;

    /**
     * Num of children process
     * @var int
     */
    public $treads = 10;




}