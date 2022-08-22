<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Models\Video;
use App\Models\VideoStatistic;
use App\Services\VideoStatistic\VideoStatisticBuilder;

class VideoStatisticCreator
{
    private VideoStatistic $videoStatistic;
    private VideoStatisticBuilder $videoStatisticBuilder;

    public function __construct(VideoStatistic $videoStatistic, VideoStatisticBuilder $videoStatisticBuilder)
    {
        $this->videoStatistic = $videoStatistic;
        $this->videoStatisticBuilder = $videoStatisticBuilder;
    }

    public function createInitialVideoStatistics(Video $video): void
    {
        foreach (VideoStatistic::TYPES as $type) {
            $this->videoStatistic->create(
                $this->videoStatisticBuilder->build(
                    $video->id,
                    $type,
                    $this->videoStatistic->getDefaultValue()
                )
            );
        }
    }
}
