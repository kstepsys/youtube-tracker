<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Models\Video;
use App\Models\VideoStatistic;
use App\Services\VideoStatistic\VideoStatisticBuilder;
use Illuminate\Database\Eloquent\Collection;

class VideoStatisticsGatherer
{
    private VideoStatistic $videoStatistic;
    private VideoStatisticBuilder $videoStatisticBuilder;

    public function __construct(
        VideoStatistic $videoStatistic,
        VideoStatisticBuilder $videoStatisticBuilder
    ) {
        $this->videoStatistic = $videoStatistic;
        $this->videoStatisticBuilder = $videoStatisticBuilder;
    }

    public function gather(int $chunkSize): void
    {
        Video::chunk($chunkSize, function (Collection $videos) {
            $videoStatistics = [];
            foreach ($videos as $video) {
                $videoStatistics = array_merge($videoStatistics, $this->gatherVideoStatistics($video));
            }

            $this->videoStatistic->createMany(collect($videoStatistics));
        });
    }

    private function gatherVideoStatistics(Video $video): array
    {
        $statistics = [];
        foreach (VideoStatistic::TYPES as $type) {
            $statistic = $this->videoStatistic->getLatestOfType($type);
            if (isset($statistic)) {
                $newStatisticValue = $statistic->value + rand(0, 10000);
                $statistics[] =
                    $this->videoStatisticBuilder->build($video->id, $statistic->type, $newStatisticValue);
            }
        }

        return $statistics;
    }
}
