<?php

declare(strict_types=1);

namespace App\Services\Video;

class VideoFilter
{
    private const PAGE_SIZE = 10;

    private string $videoName;
    private string $videoTag;
    private string $sort;

    public function __construct(string $videoName, string $videoTag, string $sort)
    {
        $this->videoName = $videoName;
        $this->videoTag = $videoTag;
        $this->sort = $sort === 'ASC' ? 'ASC' : 'DESC';
    }

    public function getPageSize(): int
    {
        return self::PAGE_SIZE;
    }

    public function getVideoName(): string
    {
        return $this->videoName;
    }

    public function getVideoTag(): string
    {
        return $this->videoTag;
    }

    public function getSort(): string
    {
        return $this->sort;
    }
}
