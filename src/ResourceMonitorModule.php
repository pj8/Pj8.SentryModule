<?php

declare(strict_types=1);

namespace Pj8\SentryModule;

use BEAR\Resource\ResourceObject;
use Ray\Di\AbstractModule;

class ResourceMonitorModule extends AbstractModule
{
    protected function configure()
    {
        $this->bindInterceptor(
            $this->matcher->subclassesOf(ResourceObject::class),
            new IsHttpMethodMatcher(),
            [ResourceInterceptor::class]
        );
    }
}
