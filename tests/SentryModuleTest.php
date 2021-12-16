<?php

namespace Pj8\SentryModule;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SentryModuleTest extends TestCase
{
    public function testInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SentryModule([]);
    }
}
