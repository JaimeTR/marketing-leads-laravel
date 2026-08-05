<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LeadTemperature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'company',
        'phone',
        'campaign',
        'ai_score',
        'ai_temperature',
        'ai_summary',
        'ai_recommended_action',
        'ai_analyzed_at',
    ];

    protected $casts = [
        'ai_score' => 'integer',
        'ai_temperature' => LeadTemperature::class,
        'ai_analyzed_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function source(): HasOne
    {
        return $this->hasOne(CampaignSource::class, 'campaign_name', 'campaign');
    }
}
