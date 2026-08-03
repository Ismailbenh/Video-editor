<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'YouTube Video Editing',
                'category' => 'youtube',
                'icon' => 'video',
                'description' => 'Full long-form edits — pacing, sound design, color, graphics.',
                'tiers' => [
                    ['name' => 'Basic', 'price' => 80, 'turnaround' => '3-4 days', 'features' => ['Up to 15 min footage', 'Basic color correction', 'Simple transitions', '1 revision']],
                    ['name' => 'Pro', 'price' => 150, 'turnaround' => '2-3 days', 'features' => ['Up to 30 min footage', 'Color grading', 'Motion graphics', 'Sound design', '2 revisions']],
                    ['name' => 'Rush', 'price' => 250, 'turnaround' => '24 hours', 'features' => ['Up to 30 min footage', 'Full color grade', 'Custom graphics', 'Priority queue', '3 revisions']],
                ],
            ],
            [
                'name' => 'Instagram Reels Editing',
                'category' => 'reels',
                'icon' => 'device-mobile',
                'description' => 'Short-form vertical edits built for retention and reach.',
                'tiers' => [
                    ['name' => 'Basic', 'price' => 35, 'turnaround' => '1-2 days', 'features' => ['Up to 60s', 'Captions', 'Trending audio sync', '1 revision']],
                    ['name' => 'Pro', 'price' => 60, 'turnaround' => '1 day', 'features' => ['Up to 90s', 'Custom captions/style', 'Text animations', '2 revisions']],
                    ['name' => 'Rush', 'price' => 100, 'turnaround' => 'Same day', 'features' => ['Up to 90s', 'Full motion design', 'Priority queue', '2 revisions']],
                ],
            ],
            [
                'name' => 'Mass / Batch Editing',
                'category' => 'batch',
                'icon' => 'package',
                'description' => 'Bulk content editing for creators and agencies posting daily.',
                'tiers' => [
                    ['name' => 'Basic', 'price' => 200, 'turnaround' => '5-7 days', 'features' => ['Up to 10 clips', 'Consistent style', 'Captions', '1 revision per clip']],
                    ['name' => 'Pro', 'price' => 400, 'turnaround' => '4-5 days', 'features' => ['Up to 20 clips', 'Consistent style', 'Custom branding', '2 revisions per clip']],
                    ['name' => 'Rush', 'price' => 700, 'turnaround' => '2-3 days', 'features' => ['Up to 20 clips', 'Full branding kit', 'Priority queue', '2 revisions per clip']],
                ],
            ],
            [
                'name' => 'Clipping',
                'category' => 'clipping',
                'icon' => 'scissors',
                'description' => 'Long-form podcast or stream footage turned into short clips.',
                'tiers' => [
                    ['name' => 'Basic', 'price' => 50, 'turnaround' => '2 days', 'features' => ['5 clips from source', 'Basic captions', '1 revision']],
                    ['name' => 'Pro', 'price' => 90, 'turnaround' => '1-2 days', 'features' => ['10 clips from source', 'Styled captions', 'Highlight selection', '2 revisions']],
                    ['name' => 'Rush', 'price' => 150, 'turnaround' => '24 hours', 'features' => ['10 clips from source', 'Styled captions', 'Priority queue', '2 revisions']],
                ],
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($service['name'])],
                [
                    ...$service,
                    'slug' => \Illuminate\Support\Str::slug($service['name']),
                    'active' => true,
                ]
            );
        }
    }
}