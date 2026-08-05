<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CampaignSource;
use App\Models\Lead;
use Illuminate\Database\Seeder;

class MarketingLeadSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['campaign_name' => 'Facebook Ads', 'source_name' => 'Meta'],
            ['campaign_name' => 'Google Search', 'source_name' => 'Google'],
            ['campaign_name' => 'TikTok Leads', 'source_name' => 'TikTok'],
        ])->each(fn (array $source) => CampaignSource::query()->updateOrCreate(
            ['campaign_name' => $source['campaign_name']],
            $source
        ));

        collect([
            ['name' => 'Juan Perez', 'email' => 'juan@test.com', 'campaign' => 'Facebook Ads', 'company' => 'Acme', 'phone' => '999111222'],
            ['name' => 'Maria Lopez', 'email' => 'maria@test.com', 'campaign' => 'Google Search', 'company' => 'Globex', 'phone' => '999333444'],
            ['name' => 'Carlos Ruiz', 'email' => 'carlos@test.com', 'campaign' => 'TikTok Leads', 'company' => 'Initech', 'phone' => '999555666'],
        ])->each(fn (array $lead) => Lead::query()->updateOrCreate(
            ['email' => $lead['email']],
            $lead
        ));
    }
}
