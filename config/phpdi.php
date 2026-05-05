<?php

declare(strict_types=1);

use function DI\autowire;
use function DI\create;

return [
    'RssAgg\\Controller\\FeedController' => autowire(),
];
