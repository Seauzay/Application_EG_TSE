<?php

namespace App\Jobs;

use App\FictitiousMessage;
use App\Team;
use App\Repositories\MessageRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alert;
    protected $team;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Team $team, FictitiousMessage $alert)
    {
        $this->alert = $alert;//
        $this->team = $team;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!is_null($this->team->start_date)){
            MessageRepository::create($this->team, $this->team->rooms()->first(), $this->alert);//
        }
    }
}
