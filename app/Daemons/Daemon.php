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

        // if ($force) {
        //     $daemon = \App\Models\Daemon::where('name', $this->dname)->first();

        //     if ($daemon) {
        //         $daemon->status = 'starting';
        //         $daemon->save();
        //     }
        // }

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

        // $daemon = \App\Models\Daemon::where('name', $this->dname)->first();

        // if ($daemon) {
        //     $daemon->updated_at = Carbon::now()->toDateTimeString();
        //     $daemon->save();
        // }
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

        // $daemon = \App\Models\Daemon::where('name', $this->dname)->first();

        // if ($daemon) {
        //     $daemon->status = 'stopped';
        //     $daemon->save();
        // }
    }

    protected function keepRunning()
    {
        // if ($this->run && !$this->CheckDbSignal()) {
        //     $this->shutdown("DB Signal");
        // }

        return $this->run;
    }

    // private function CheckDbSignal()
    // {
    //     $ret = false;

    //     $daemon = \App\Models\Daemon::where('name', $this->dname)->first();

    //     if ($daemon) {
    //         if ($daemon->status == 'running')
    //             $ret = true;
    //     }

    //     return $ret;
    // }

    private function register()
    {
        $this->run = true;
        // $daemon = \App\Models\Daemon::where('name', $this->dname)->first();

        // if (!$daemon) {
        //     $daemon = new \App\Models\Daemon();

        //     $daemon->status = 'starting';
        //     $daemon->name = $this->dname;
        // }

        // if ($daemon->status == 'starting') {
        //     $daemon->status = 'running';
        //     $daemon->start_date = Carbon::now()->toDateTimeString();
        //     $daemon->pid = getmypid();

        //     $daemon->save();
        //     $this->run = true;
        // } else if ($daemon->status != 'stopped') {
        //     $class = get_class($this);
        //     $this->log()->error("couldn't start $class, current status is $daemon->status.");
        // }

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
