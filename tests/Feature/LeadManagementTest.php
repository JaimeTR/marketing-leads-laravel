<?php

declare(strict_types=1);

use App\Contracts\LeadAiAnalyzerContract;
use App\Data\LeadInsightData;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a lead and stores ai insight', function (): void {
    $this->mock(LeadAiAnalyzerContract::class)
        ->shouldReceive('analyzeLead')
        ->once()
        ->andReturn(new LeadInsightData(
            score: 82,
            temperature: 'hot',
            summary: 'Alta probabilidad de compra.',
            recommendedAction: 'Contactar antes de 24 horas.',
            signals: ['complete_contact_data']
        ));

    $response = $this->post('/leads', [
        'name' => 'Ana Torres',
        'email' => 'ana@example.com',
        'company' => 'Acme',
        'phone' => '999888777',
        'campaign' => 'Google Search',
    ]);

    $response->assertRedirect();

    expect(Lead::query()->where('email', 'ana@example.com')->first())
        ->ai_score->toBe(82)
        ->ai_temperature->value->toBe('hot');
});

it('rejects duplicate email addresses', function (): void {
    Lead::factory()->create(['email' => 'lead@example.com']);

    $response = $this->post('/leads', [
        'name' => 'Lead Duplicado',
        'email' => 'lead@example.com',
        'campaign' => 'Facebook Ads',
    ]);

    $response->assertSessionHasErrors('email');
});
