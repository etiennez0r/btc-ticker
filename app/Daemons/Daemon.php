<?php

namespace App\Daemons;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DaemonError;

abstract class Daemon
{
    protected $timemark;
    protected $period;
    protected $logchannel = null;
    protected $lastError = null;
    protected $dname = null;
    
    private $sleeptime = 1;
    private $run = false;
    

    abstract protected function run();

    public function loop($force = false)
    {
        if (\App::runningInConsole() != true)
            exit;

        if (!$this->register()) {
            exit;
        }

        $this->log()->notice("***" . get_class($this) . " started, iterating every $this->period seconds, pid: " . getmypid() . "***");

        if (PHP_OS_FAMILY == 'Linux') {
            pcntl_async_signals(true);

            pcntl_signal(SIGINT, [$this, 'shutdown']);
            pcntl_signal(SIGTERM, [$this, 'shutdown']);
            $this->log()->debug("running on linux");
        } else
            $this->log()->debug("running on windows");

        while ($this->keepRunning()) {
            if ($this->runtime()) {

                $this->mark();

                if (!$this->run()) {
                    $this->log()->notice('Daemon cycle interrupted.');
                    continue;
                }
                // $this->log()->debug('Finished daemon cycle in ' . $this->elapsed() . ' seconds.');
            }

            sleep($this->sleeptime);   // iteracion minima
        }

        $this->log()->notice(get_class($this) . " exiting infinite loop");
    }

    private function runtime()
    {
        return ($this->elapsed() >= $this->period);
    }

    private function mark()
    {
        $this->timemark = time();

    }

    private function elapsed()
    {
        return time() - $this->timemark;
    }

    protected function log()
    {
        return Log::channel($this->logchannel);
    }

    public function shutdown($reason = null)
    {
        $this->log()->notice("***Shutting down daemon with reason: $reason***");
        $this->run = false;

    }

    protected function keepRunning()
    {

        return $this->run;
    }

    private function register()
    {
        $this->run = true;

        return $this->run;
    }

    protected function reportError($s)
    {
        if (PHP_OS_FAMILY != 'Linux')
            return;
            
        $tmp = substr($s, 0, 100);

        if ($this->lastError == $tmp)
            return;
        
        Notification::route('mail', 'etiennez0r@gmail.com')
            ->notify(new DaemonError($s, $this->dname));

        $this->lastError = $tmp;
    }
}
