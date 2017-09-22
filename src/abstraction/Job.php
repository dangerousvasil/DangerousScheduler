<?php

namespace DangerousScheduler\abstraction;


interface Job
{

    public function validateJob() : bool;

    public function acceptJob();

    public function rejectJob();

    public function doJob();
}