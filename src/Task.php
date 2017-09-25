<?php

namespace DangerousScheduler;


class Task implements abstraction\Task
{

    /**
     * @var string
     */
    public $name;
    /**
     * @var array
     */
    public $arguments = [];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return  []
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}