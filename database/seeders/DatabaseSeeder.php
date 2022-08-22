<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Video;
use App\Models\VideoTag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Channel::factory(10)->create()->each(function (Channel $channel) {
            Video::factory(50)->create(['channel_id' => $channel->id])->each(function (Video $video) {
                VideoTag::factory(5)->create(['video_id' => $video->id]);
            });
        });
    }
}
