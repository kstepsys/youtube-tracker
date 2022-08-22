<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'subscribers_count'];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function getChannelStatistics(Video $video): Collection
    {
        $channel = $this->whereId($video->channel_id)
            ->with(['videos.statistics' => function (HasMany $query) {
                $query->whereRaw("video_statistics.created_at <= DATE_ADD(created_at, INTERVAL '1' HOUR)");
                $query->where('video_statistics.type', 'views');
                $query->orderBy('video_statistics.created_at', 'DESC');
            }])
            ->first();

        $videoStatistics = collect();
        foreach ($channel->videos as $video) {
            if ($video->statistics->isEmpty()) {
                continue;
            }

            $videoStatistics->add($video->statistics->first());
        }

        return $videoStatistics;
    }
}
