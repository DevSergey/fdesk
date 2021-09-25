<?php
namespace Symfony\Component\Translation\Loader;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Config\Resource\FileResource;
class IcuDatFileLoader extends IcuResFileLoader
{
    public function load($resource, $locale, $domain = 'messages')
    {
        if (!stream_is_local($resource.'.dat')) {
            throw new InvalidResourceException(sprintf('This is not a local file "%s".', $resource));
        }
        if (!file_exists($resource.'.dat')) {
            throw new NotFoundResourceException(sprintf('File "%s" not found.', $resource));
        }
        try {
            $rb = new \ResourceBundle($locale, $resource);
        } catch (\Exception $e) {
            $rb = null;
        }
        if (!$rb) {
            throw new InvalidResourceException(sprintf('Cannot load resource "%s"', $resource));
        } elseif (intl_is_failure($rb->getErrorCode())) {
            throw new InvalidResourceException($rb->getErrorMessage(), $rb->getErrorCode());
        }
        $messages = $this->flatten($rb);
        $catalogue = new MessageCatalogue($locale);
        $catalogue->add($messages, $domain);
        $catalogue->addResource(new FileResource($resource.'.dat'));
        return $catalogue;
    }
}
