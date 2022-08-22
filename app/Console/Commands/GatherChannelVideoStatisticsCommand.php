<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Video\VideoPerformanceCalculator;
use App\Services\Video\VideoStatisticsGatherer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GatherChannelVideoStatisticsCommand extends Command
{
    private const CHUNK_SIZE = 100;

    protected $signature = 'statistics:gather';
    protected $description = 'Gathers statistics for all all channel videos in the system';

    private VideoStatisticsGatherer $videoStatisticsGatherer;
    private VideoPerformanceCalculator $videoPerformanceCalculator;

    public function __construct(
        VideoStatisticsGatherer $videoStatisticsGatherer,
        VideoPerformanceCalculator $videoPerformanceCalculator
    ) {
        $this->videoStatisticsGatherer = $videoStatisticsGatherer;
        $this->videoPerformanceCalculator = $videoPerformanceCalculator;
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle(): int
    {
        DB::beginTransaction();

        try {
            $this->videoStatisticsGatherer->gather(self::CHUNK_SIZE);
            $this->videoPerformanceCalculator->calculatePerformances(self::CHUNK_SIZE);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw new Exception("Something went wrong: {$exception->getMessage()}");
        }

        return 0;
    }
}
