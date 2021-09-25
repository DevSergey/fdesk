<?php
namespace Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
class BinaryFileResponse extends Response
{
    protected static $trustXSendfileTypeHeader = false;
    protected $file;
    protected $offset;
    protected $maxlen;
    protected $deleteFileAfterSend = false;
    public function __construct($file, $status = 200, $headers = array(), $public = true, $contentDisposition = null, $autoEtag = false, $autoLastModified = true)
    {
        parent::__construct(null, $status, $headers);
        $this->setFile($file, $contentDisposition, $autoEtag, $autoLastModified);
        if ($public) {
            $this->setPublic();
        }
    }
    public static function create($file = null, $status = 200, $headers = array(), $public = true, $contentDisposition = null, $autoEtag = false, $autoLastModified = true)
    {
        return new static($file, $status, $headers, $public, $contentDisposition, $autoEtag, $autoLastModified);
    }
    public function setFile($file, $contentDisposition = null, $autoEtag = false, $autoLastModified = true)
    {
        if (!$file instanceof File) {
            if ($file instanceof \SplFileInfo) {
                $file = new File($file->getPathname());
            } else {
                $file = new File((string) $file);
            }
        }
        if (!$file->isReadable()) {
            throw new FileException('File must be readable.');
        }
        $this->file = $file;
        if ($autoEtag) {
            $this->setAutoEtag();
        }
        if ($autoLastModified) {
            $this->setAutoLastModified();
        }
        if ($contentDisposition) {
            $this->setContentDisposition($contentDisposition);
        }
        return $this;
    }
    public function getFile()
    {
        return $this->file;
    }
    public function setAutoLastModified()
    {
        $this->setLastModified(\DateTime::createFromFormat('U', $this->file->getMTime()));
        return $this;
    }
    public function setAutoEtag()
    {
        $this->setEtag(sha1_file($this->file->getPathname()));
        return $this;
    }
    public function setContentDisposition($disposition, $filename = '', $filenameFallback = '')
    {
        if ($filename === '') {
            $filename = $this->file->getFilename();
        }
        $dispositionHeader = $this->headers->makeDisposition($disposition, $filename, $filenameFallback);
        $this->headers->set('Content-Disposition', $dispositionHeader);
        return $this;
    }
    public function prepare(Request $request)
    {
        $this->headers->set('Content-Length', $this->file->getSize());
        if (!$this->headers->has('Accept-Ranges')) {
            $this->headers->set('Accept-Ranges', $request->isMethodSafe() ? 'bytes' : 'none');
        }
        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', $this->file->getMimeType() ?: 'application/octet-stream');
        }
        if ('HTTP/1.0' != $request->server->get('SERVER_PROTOCOL')) {
            $this->setProtocolVersion('1.1');
        }
        $this->ensureIEOverSSLCompatibility($request);
        $this->offset = 0;
        $this->maxlen = -1;
        if (self::$trustXSendfileTypeHeader && $request->headers->has('X-Sendfile-Type')) {
            $type = $request->headers->get('X-Sendfile-Type');
            $path = $this->file->getRealPath();
            if (strtolower($type) == 'x-accel-redirect') {
                foreach (explode(',', $request->headers->get('X-Accel-Mapping', '')) as $mapping) {
                    $mapping = explode('=', $mapping, 2);
                    if (2 == count($mapping)) {
                        $pathPrefix = trim($mapping[0]);
                        $location = trim($mapping[1]);
                        if (substr($path, 0, strlen($pathPrefix)) == $pathPrefix) {
                            $path = $location.substr($path, strlen($pathPrefix));
                            break;
                        }
                    }
                }
            }
            $this->headers->set($type, $path);
            $this->maxlen = 0;
        } elseif ($request->headers->has('Range')) {
            if (!$request->headers->has('If-Range') || $this->getEtag() == $request->headers->get('If-Range')) {
                $range = $request->headers->get('Range');
                $fileSize = $this->file->getSize();
                list($start, $end) = explode('-', substr($range, 6), 2) + array(0);
                $end = ('' === $end) ? $fileSize - 1 : (int) $end;
                if ('' === $start) {
                    $start = $fileSize - $end;
                    $end = $fileSize - 1;
                } else {
                    $start = (int) $start;
                }
                if ($start <= $end) {
                    if ($start < 0 || $end > $fileSize - 1) {
                        $this->setStatusCode(416);
                    } elseif ($start !== 0 || $end !== $fileSize - 1) {
                        $this->maxlen = $end < $fileSize ? $end - $start + 1 : -1;
                        $this->offset = $start;
                        $this->setStatusCode(206);
                        $this->headers->set('Content-Range', sprintf('bytes %s-%s/%s', $start, $end, $fileSize));
                        $this->headers->set('Content-Length', $end - $start + 1);
                    }
                }
            }
        }
        return $this;
    }
    public function sendContent()
    {
        if (!$this->isSuccessful()) {
            parent::sendContent();
            return;
        }
        if (0 === $this->maxlen) {
            return;
        }
        $out = fopen('php:
        $file = fopen($this->file->getPathname(), 'rb');
        stream_copy_to_stream($file, $out, $this->maxlen, $this->offset);
        fclose($out);
        fclose($file);
        if ($this->deleteFileAfterSend) {
            unlink($this->file->getPathname());
        }
    }
    public function setContent($content)
    {
        if (null !== $content) {
            throw new \LogicException('The content cannot be set on a BinaryFileResponse instance.');
        }
    }
    public function getContent()
    {
        return false;
    }
    public static function trustXSendfileTypeHeader()
    {
        self::$trustXSendfileTypeHeader = true;
    }
    public function deleteFileAfterSend($shouldDelete)
    {
        $this->deleteFileAfterSend = $shouldDelete;
        return $this;
    }
}
