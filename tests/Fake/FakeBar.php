<?php

declare(strict_types=1);

namespace Pj8\SentryModule\Fake;

use BEAR\Resource\ResourceObject;
use BEAR\Resource\Uri;
use Pj8\SentryModule\Annotation\Monitorable;

class FakeBar
{
    /**
     * @Monitorable()
     */
    #[Monitorable]
    public function foo(): void
    {
    }
}
