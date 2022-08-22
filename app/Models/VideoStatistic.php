<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class VideoStatistic extends Model
{
    use HasFactory;

    const TYPES = [
        self::TYPE_VIEWS,
        self::TYPE_LIKES,
        self::TYPE_DISLIKES,
        self::TYPE_COMMENT_COUNT,
    ];
    private const TYPE_VIEWS = 'views';
    private const TYPE_LIKES = 'likes';
    private const TYPE_DISLIKES = 'dislikes';
    private const TYPE_COMMENT_COUNT = 'comment_count';
    private const DEFAULT_VALUE = 0;

    protected $fillable = ['type', 'value'];

    public function getLatestOfType(string $type): ?VideoStatistic
    {
        return VideoStatistic::where('type', $type)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function create(VideoStatistic $videoStatistic): void
    {
        $videoStatistic->save();
    }

    public function createMany(Collection $videoStatistics): void
    {
        $now = new DateTime();
        $data = $videoStatistics->toArray();
        foreach ($data as &$dataEntry) {
            $dataEntry['created_at'] = $now;
            $dataEntry['updated_at'] = $now;
        }

        VideoStatistic::insert($data);
    }

    public function getDefaultValue(): int
    {
        return self::DEFAULT_VALUE;
    }
}
