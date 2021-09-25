<?php
namespace Symfony\Component\HttpKernel\Exception;
class PreconditionRequiredHttpException extends HttpException
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(428, $message, $previous, array(), $code);
    }
}
