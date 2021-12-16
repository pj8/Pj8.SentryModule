<?php

declare(strict_types=1);

namespace Pj8\SentryModule\Fake;

use BEAR\Resource\ResourceObject;
use BEAR\Resource\Uri;
use Pj8\SentryModule\Annotation\Monitorable;

class FakeBaz extends ResourceObject
{
    public function __construct()
    {
        $this->uri = new Uri('app://self/baz');
    }

    /**
     * @return static
     */
    public function onGet()
    {
        return $this;
    }

    /**
     * @Monitorable()
     */
    public function foo(): void
    {
    }
}
