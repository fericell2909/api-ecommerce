<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $request;
    protected $copia;
    protected $template;
    protected $asunto;
    protected $time;
    public function __construct($request, $copia, $template, $asunto)
    {
        //
        $this->request = $request;
        $this->copia = $copia;
        $this->template = $template;
        $this->asunto = $asunto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = $this->template;
        return $this->subject($this->asunto)->view($template, $this->request);
    }
}
