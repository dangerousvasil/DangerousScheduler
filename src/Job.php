<?php

namespace DangerousScheduler;


use DangerousScheduler\abstraction\Task;

abstract class Job implements abstraction\Job
{

    /**
     * @var Task
     */
    protected $task = null;

    public function __construct()
    {
    }

    final public function run()
    {

        switch ($pid = pcntl_fork()) {
            case -1:
                // @fail
                die('Fork failed');
                break;

            case 0:
                // @child: Include() misbehaving code here
                print "FORK: Child Job " . __CLASS__ . " preparing to nuke...".PHP_EOL;
                $this->doJob();
                break;

            default:
                // @parent
                print "FORK: Master Job, letting the child run amok...".PHP_EOL;

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

    public function __destruct()
    {
        echo 'TODO: Implement __destruct() method.'.PHP_EOL;
    }

}