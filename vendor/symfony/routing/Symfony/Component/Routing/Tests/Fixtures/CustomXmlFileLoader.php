<?php
namespace Symfony\Component\Routing\Tests\Fixtures;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Config\Util\XmlUtils;
class CustomXmlFileLoader extends XmlFileLoader
{
    protected function loadFile($file)
    {
        return XmlUtils::loadFile($file, function () { return true; });
    }
}
