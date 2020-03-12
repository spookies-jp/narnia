<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Mail\ExceptionOccured;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldMailReport($exception)) {
            $this->sendEmail($exception); // sends an email
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }


    /**
     * Sends an email to the developer about the exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function sendEmail(Exception $exception)
    {
        // sending email
        try {
            //$e = FlattenException::create($exception);
            //$handler = new SymfonyExceptionHandler();
            //$html = $handler->getHtml($e);
            $text       = $exception->__toString();
            $stacktrace = $exception->getTraceAsString();
            $request    = Request::capture();
            $gets       = $request->query->all();
            $posts      = $request->request->all();
            $cookies    = $request->cookies->all();
            $servers    = $request->server->all();
            $mail = new ExceptionOccured($text, $stacktrace, $gets, $posts, $cookies, $_SESSION, $servers);
            $branch = exec("git symbolic-ref --short HEAD");
            $subject = "[{$branch}] Exception Occured: " . $exception->getMessage() . " at " . $exception->getFile() . ":" . $exception->getLine();
            $subject = substr($subject, 0, 200);    // Backlog タイトルの制限
            $mail->subject($subject);

            // FIXME mail address, subject
            Mail::to('test@test.com')->send($mail);

        } catch (Exception $ex) {
            //dd($ex);
        }
    }

    /**
     * 本番環境
     */
    public function shouldMailReport(Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) return false;
        if ($exception instanceof HttpException) return false;

        return true;
    }

}
