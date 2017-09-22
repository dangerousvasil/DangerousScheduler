<?php

namespace DangerousScheduler;


use DangerousScheduler\abstraction\Repository;

final class Server implements abstraction\Server
{
    protected $settings = null;

    private $childs = [];

    public function __construct(abstraction\ServerSettings $settings)
    {
        $this->settings = $settings;
    }

    final public function run()
    {
        while (1) {
            $this->process();
        }
    }

    final private function process()
    {
        if (!function_exists('pcntl_fork')) {
            die('PCNTL functions not available on this PHP installation');
        }

        for ($x = 0; $x < ($this->settings->treads - count($this->childs)); $x++) {
            switch ($pid = pcntl_fork()) {
                case -1:
                    // @fail
                    throw new \Exception('Fork failed');
                    break;

                case 0:
                    // @child: Include() misbehaving code here
                    print "FORK: Master Job #{$x} preparing to nuke..." . PHP_EOL;
                    if (!is_subclass_of($this->settings->repositoryClassName, Repository::class)) {
                        throw new \Exception('Repository Class must implements ' . Repository::class);
                    }
                    $class = $this->settings->repositoryClassName;
                    $repository = new $class();
                    $task = $repository->getTask();

                    if (!is_subclass_of($task->name, Job::class)) {
                        throw new \Exception('Job Class must implements ' . Job::class);
                    }
                    $job = new $task->name();

                    $job->run();
                    // Not need continue you are done
                    die('Job Done!' . PHP_EOL);
                    break;

                default:
                    // @parent
                    print "FORK: Parent, letting the child run amok..." . PHP_EOL;
                    $this->childs[] = $pid;
                    break;
            }
        }

        foreach ($this->childs as $key => $pid) {
            $res = pcntl_waitpid($pid, $status, WNOHANG);

            // If the process has already exited
            if ($res == -1 || $res > 0) {
                unset($this->childs[$key]);
            }
        }

        sleep(1);
    }
}