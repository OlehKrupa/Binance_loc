<?php

namespace App\Services;

use App\Repositories\NewsRepository;
use App\Services\BaseService;

class NewsService extends BaseService
{
    public function __construct(NewsRepository $repo)
    {
        $this->repo = $repo;
    }
}