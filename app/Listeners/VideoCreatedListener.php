<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\VideoCreatedEvent;
use App\Models\Video;
use App\Services\Video\VideoStatisticCreator;

class VideoCreatedListener
{
    private VideoStatisticCreator $videoStatisticCreatorService;

    public function __construct(VideoStatisticCreator $videoStatisticCreatorService)
    {
        $this->videoStatisticCreatorService = $videoStatisticCreatorService;
    }

    public function handle(VideoCreatedEvent $videoCreatedEvent): void
    {
        $this->videoStatisticCreatorService->createInitialVideoStatistics($videoCreatedEvent->getVideo());
    }
}
