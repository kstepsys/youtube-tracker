<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Models\Video;
use Illuminate\Pagination\LengthAwarePaginator;

class VideoRetriever
{
    private Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function getVideos(VideoFilter $videoFilter): LengthAwarePaginator
    {
        return $this->video->getFilteredVideos($videoFilter);
    }
}
