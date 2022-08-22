<?php

declare(strict_types=1);

namespace App\Services\VideoStatistic;

use App\Models\VideoStatistic;

class VideoStatisticBuilder
{
    public function build(int $videoId, string $type, int $value): VideoStatistic
    {
        $videoStatistic = new VideoStatistic;
        $videoStatistic->video_id = $videoId;
        $videoStatistic->type = $type;
        $videoStatistic->value = $value;

        return $videoStatistic;
    }
}
