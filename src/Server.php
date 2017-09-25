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
            throw new \Exception('PCNTL functions not available on this PHP installation');
        }

        foreach ($this->childs as $key => $pid) {
            $res = pcntl_waitpid($pid, $status, WNOHANG);

            // If the process has already exited
            if ($res == -1 || $res > 0) {
                unset($this->childs[$key]);
            }
        }

        for ($x = 1; $x < ($this->settings->treads - count($this->childs)); $x++) {
            switch ($pid = pcntl_fork()) {
                case -1:
                    // @fail
                    throw new \Exception('Fork failed');
                    break;

                case 0:
                    // @child: Include() misbehaving code here
                    $this->log("FORK: Master Job #{$x} preparing to nuke...");
                    if (!is_subclass_of($this->settings->repositoryClassName, Repository::class)) {
                        throw new \Exception('Repository Class must implements ' . Repository::class);
                    }
                    $class = $this->settings->repositoryClassName;
                    $repository = new $class();
                    $task = $repository->getTask();
                    if ($task) {
                        $class = $task->getName();
                        if (!is_subclass_of($class, Job::class)) {
                            throw new \Exception('Job Class must implements ' . Job::class);
                        }
                        $job = new $class($task->getArguments());
                        $job->task = $task;
                        $job->settings = $this->settings;
                        $job->run();
                    }
                    // Not need continue you are done
                    $this->log('Job Done!');
                    die();
                    break;

                default:
                    // @parent
                    $this->log("FORK: Parent, letting the child run amok..." . PHP_EOL);
                    $this->childs[] = $pid;
                    break;
            }
        }

        sleep(2);
    }

    public function log($message)
    {
        if ($this->settings->log) {
            $this->settings->logClass::log($message);
        }
    }
}