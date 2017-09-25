<?php

namespace DangerousScheduler\abstraction;


interface Task
{
    public function getName();
    public function getArguments();

}