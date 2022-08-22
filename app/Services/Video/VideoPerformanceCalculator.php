<?php

declare(strict_types=1);

namespace App\Services\Video;

use App\Models\Channel;
use App\Models\Video;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as SupportCollection;

class VideoPerformanceCalculator
{
    private Video $video;
    private Channel $channel;

    public function __construct(Video $video, Channel $channel)
    {
        $this->video = $video;
        $this->channel = $channel;
    }

    public function calculatePerformances(int $chunkSize): void
    {
        Video::with(['statistics' => function (HasMany $query) {
            $query->where('video_statistics.created_at', '<=', (new DateTime())->modify('+1 hour'));
            $query->where('type', 'views');
        }])->chunk($chunkSize, function ($videos) {
            foreach ($videos as $video) {
                if (!$video['statistics']->isEmpty()) {
                    $video->performance = $this->calculateVideoPerformance($video);

                    $this->video->updateVideo($video);
                }
            }
        });
    }

    private function calculateVideoPerformance(Video $video): float
    {
        /** @var Collection $videoStatistics */
        $videoStatistics = $video['statistics'];

        $hourlyStatistic = $videoStatistics->max('value');
        $channelStatistics = $this->channel->getChannelStatistics($video);
        $median = $this->calculateMedian($channelStatistics);

        return (float)number_format($hourlyStatistic / $median, 2);
    }

    private function calculateMedian(SupportCollection $videoStatistics): float
    {
        if ($videoStatistics->isEmpty()) {
            return 1;
        }

        $videoStatistics = $videoStatistics->sortBy('value');

        $length = count($videoStatistics);
        $secondHalfLength = $length / 2;
        $firstHalfLength = $secondHalfLength - 1;
        $firstHalf = $videoStatistics[$firstHalfLength];
        $secondHalf = $videoStatistics[$secondHalfLength];

        return ($firstHalf->value + $secondHalf->value) / 2;
    }
}
