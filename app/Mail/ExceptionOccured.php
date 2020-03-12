<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionOccured extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The body of the message.
     *
     * @var string
     */
    public $content;
    public $stacktrace;
    public $gets;
    public $posts;
    public $cookies;
    public $sessions;
    public $servers;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $content, string $stacktrace, array $gets, array $posts, array $cookies, ?array $sessions, array $servers)
    {
        //
        $this->content      = $content;
        $this->stacktrace   = $stacktrace;
        $this->gets         = $gets;
        $this->posts        = $posts;
        $this->cookies      = $cookies;
        $this->sessions     = $sessions;
        $this->servers      = $servers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('emails.exception')
        return $this->text('emails.exception')
                    ->with('content',   $this->content)
                    ->with('stacktrace',$this->stacktrace)
                    ->with('gets',      $this->gets)
                    ->with('posts',     $this->posts)
                    ->with('cookies',   $this->cookies)
                    ->with('sessions',  $this->sessions)
                    ->with('servers',   $this->servers)
               ;
    }

}
