<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\News;

class NewsRepository extends BaseRepository
{
    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function findBySourceUrl($sourceUrl)
    {
        return News::where('source', $sourceUrl)->first();
    }
}
