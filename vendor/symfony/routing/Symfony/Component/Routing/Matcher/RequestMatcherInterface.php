<?php
namespace Symfony\Component\Routing\Matcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
interface RequestMatcherInterface
{
    public function matchRequest(Request $request);
}
