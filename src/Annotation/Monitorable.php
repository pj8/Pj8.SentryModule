<?php

declare(strict_types=1);

namespace Pj8\SentryModule\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target("METHOD")
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Monitorable
{
}
