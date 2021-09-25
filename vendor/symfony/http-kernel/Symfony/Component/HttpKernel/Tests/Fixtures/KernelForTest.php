<?php
namespace Symfony\Component\HttpKernel\Tests\Fixtures;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
class KernelForTest extends Kernel
{
    public function getBundleMap()
    {
        return $this->bundleMap;
    }
    public function registerBundles()
    {
        return array();
    }
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
    public function isBooted()
    {
        return $this->booted;
    }
}