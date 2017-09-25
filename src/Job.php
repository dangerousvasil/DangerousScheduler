<?php

namespace DangerousScheduler;


use DangerousScheduler\abstraction\Task;

abstract class Job implements abstraction\Job
{

    /**
     * @var Task
     */
    public $task     = null;
    public $settings = null;

    public function __construct()
    {
    }

    final public function run()
    {

        switch ($pid = pcntl_fork()) {
            case -1:
                // @fail
                $this->log('Fork failed');
                die();
                break;

            case 0:
                // @child: Include() misbehaving code here
                $this->log("FORK: Child Job " . get_class($this) . " preparing to nuke...");
                $this->doJob();
                break;

            default:
                // @parent
                $this->log("FORK: Master Job, letting the child run amok...");
                do {
                    $res = pcntl_waitpid($pid, $status, WNOHANG);

                    // If the process has already exited
                    $exit = ($res == -1 || $res > 0);

                    sleep(1);
                } while (!$exit);

                if ($this->validateJob()) {
                    $this->acceptJob();

                } else {
                    $this->rejectJob();
                }
                break;
        }
    }

    public function log($message)
    {
        if ($this->settings->log) {
            $this->settings->logClass::log($message);
        }
    }

}