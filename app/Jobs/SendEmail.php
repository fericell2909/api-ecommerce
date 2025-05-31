<?php

namespace App\Jobs;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as FMAil;
use App\Mail\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $body;
    protected $copia;
    protected $template;
    protected $asunto;
    protected $email;
    protected $time;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($body, $template, $asunto, $email, $copia)
    {
        $this->body = $body;
        $this->template = $template;
        $this->asunto = $asunto;
        $this->email = $email;
        $this->copia = $copia;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '256M');

        Log::info("INICIO ENVIO EMAIL JOB  ===> PARA : " . $this->email);


        try {

            Log::info("INICIO ENVIO EMAIL JOB  ===> PARA : " . $this->email);
            $mail = new Mail(
                $this->body,
                $this->copia,
                $this->template,
                $this->asunto
            );
            Log::info("RESULTADO DEL ENVIO  : " . var_dump(FMAil::to($this->email)->send($mail)));

        } catch (\Exception $e) {

            Log::error("ERROR :::  ENVIO EMAIL JOB  ===> PARA " . $this->email);
            Log::info($e->getMessage());
        }
        Log::info("FIN :::  ENVIO EMAIL JOB  ===> PARA : " . $this->email);
    }
}
