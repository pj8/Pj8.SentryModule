<?php

declare(strict_types=1);

namespace Pj8\SentryModule\Fake;

use BEAR\Resource\ResourceObject;
use BEAR\Resource\Uri;

class FakeRo extends ResourceObject
{
    private FakeBaz $baz;
    private FakeBar $bar;

    public function __construct(FakeBaz $baz, FakeBar $bar)
    {
        $this->baz = $baz;
        $this->bar = $bar;
        // @see \Pj8\SentryModule\ResourceSpanFactory
        $this->uri = new Uri('app://self/foo');
    }

    public function bar(): void
    {
    }

    public function onGet(): ResourceObject
    {
        $this->baz->onGet();
        $this->baz->foo();
        $this->bar->foo();
        return $this;
    }

    public function onPost(): void
    {
    }

    public function onPut(): void
    {
    }

    public function onDelete(): void
    {
    }
}
