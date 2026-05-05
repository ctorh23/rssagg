<?php

declare(strict_types=1);

return function (\FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/feed/list', ['Feed', 'list']);
    $r->addRoute('GET', '/feed/manage', ['Feed', 'add']);
    $r->addRoute('GET', '/feed/manage/{id:\d+}', ['Feed', 'edit']);
    $r->addRoute('GET', '/feed/delete/{id:\d+}', ['Feed', 'del']);
};
