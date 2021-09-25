<?php
namespace Symfony\Component\HttpKernel\Exception;
class UnauthorizedHttpException extends HttpException
{
    public function __construct($challenge, $message = null, \Exception $previous = null, $code = 0)
    {
        $headers = array('WWW-Authenticate' => $challenge);
        parent::__construct(401, $message, $previous, $headers, $code);
    }
}