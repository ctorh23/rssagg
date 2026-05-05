<?php

declare(strict_types=1);

namespace RssAgg\Controller;

use Symfony\Component\HttpFoundation\Response;

final class FeedController
{
    public function list(): Response
    {
        return new Response()->setContent('List');
    }

    public function add(): Response
    {
        return new Response()->setContent('Add');
    }

    public function edit(int $id): Response
    {
        return new Response()->setContent('Edit ' . $id);
    }

    public function del(int $id): Response
    {
        return new Response()->setContent('Del ' . $id);
    }
}
