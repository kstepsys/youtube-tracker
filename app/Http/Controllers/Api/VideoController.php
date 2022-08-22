<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\Video\VideoFilter;
use App\Services\Video\VideoRetriever;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController
{
    public function index(Request $request, VideoRetriever $videoRetriever): JsonResponse
    {
        $videoFilter = new VideoFilter(
            $request->query('videoNameFilter') ?? '',
            $request->query('videoTagFilter') ?? '',
            $request->query('sort')
        );

        return response()->json($videoRetriever->getVideos($videoFilter));
    }
}
