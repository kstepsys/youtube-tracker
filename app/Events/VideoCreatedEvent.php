<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Video;
use Illuminate\Foundation\Events\Dispatchable;

class VideoCreatedEvent
{
    use Dispatchable;

    private Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function getVideo(): Video
    {
        return $this->video;
    }
}
