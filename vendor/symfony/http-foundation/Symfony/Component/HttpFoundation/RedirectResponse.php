<?php
namespace Symfony\Component\HttpFoundation;
class RedirectResponse extends Response
{
    protected $targetUrl;
    public function __construct($url, $status = 302, $headers = array())
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }
        parent::__construct('', $status, $headers);
        $this->setTargetUrl($url);
        if (!$this->isRedirect()) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code is not a redirect ("%s" given).', $status));
        }
    }
    public static function create($url = '', $status = 302, $headers = array())
    {
        return new static($url, $status, $headers);
    }
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }
    public function setTargetUrl($url)
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }
        $this->targetUrl = $url;
        $this->setContent(
            sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="1;url=%1$s" />
        <title>Redirecting to %1$s</title>
    </head>
    <body>
        Redirecting to <a href="%1$s">%1$s</a>.
    </body>
</html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8')));
        $this->headers->set('Location', $url);
        return $this;
    }
}