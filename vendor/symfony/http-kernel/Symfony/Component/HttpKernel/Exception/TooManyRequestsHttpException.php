<?php
namespace Symfony\Component\HttpKernel\Exception;
class TooManyRequestsHttpException extends HttpException
{
    public function __construct($retryAfter = null, $message = null, \Exception $previous = null, $code = 0)
    {
        $headers = array();
        if ($retryAfter) {
            $headers = array('Retry-After' => $retryAfter);
        }
        parent::__construct(429, $message, $previous, $headers, $code);
    }
}
