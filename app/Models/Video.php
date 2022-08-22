<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\VideoCreatedEvent;
use App\Services\Video\VideoFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'length'];

    protected $dispatchesEvents = [
        'created' => VideoCreatedEvent::class,
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function videoTags(): HasMany
    {
        return $this->hasMany(VideoTag::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(VideoStatistic::class);
    }

    public function updateVideo(Video $video): void
    {
        $video->save();
    }

    public function getFilteredVideos(VideoFilter $videoFilter): LengthAwarePaginator
    {
        return Video::where('name', 'like', "%{$videoFilter->getVideoName()}%")
            ->whereHas('videoTags', function ($query) use ($videoFilter) {
                $query->where('name', 'like', "%{$videoFilter->getVideoTag()}%");
            })
            ->with('videoTags')
            ->with('channel')
            ->orderBy('performance', $videoFilter->getSort())
            ->paginate($videoFilter->getPageSize());
    }
}
