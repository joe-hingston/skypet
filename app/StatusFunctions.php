<?php


namespace App;


use Symfony\Component\Process\Process;

class StatusFunctions
{

    public function runCommand  ($commandstring){


        $process = new Process($commandstring);
        $process->setTimeout(3600);
        $process->setIdleTimeout(300);
        $process->setWorkingDirectory(base_path());
        $process->run(function ($type, $buffer){
            $this->result = $buffer;
        });

        return !$this->result ? false : true;
    }

    public function runRedisCheck($commandstring){


        $process = new Process($commandstring);
        $process->setTimeout(3600);
        $process->setIdleTimeout(300);
        $process->setWorkingDirectory(base_path());
        $process->run(function ($type, $buffer){
            $this->redis = preg_replace('~[\r\n]+~', '', $buffer);;

        });

        return (!$this->result = "PONG") ? false : true;
    }
}
