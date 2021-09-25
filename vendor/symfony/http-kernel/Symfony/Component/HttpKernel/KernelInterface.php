<?php
namespace Symfony\Component\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
interface KernelInterface extends HttpKernelInterface, \Serializable
{
    public function registerBundles();
    public function registerContainerConfiguration(LoaderInterface $loader);
    public function boot();
    public function shutdown();
    public function getBundles();
    public function isClassInActiveBundle($class);
    public function getBundle($name, $first = true);
    public function locateResource($name, $dir = null, $first = true);
    public function getName();
    public function getEnvironment();
    public function isDebug();
    public function getRootDir();
    public function getContainer();
    public function getStartTime();
    public function getCacheDir();
    public function getLogDir();
    public function getCharset();
}
