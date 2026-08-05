<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_name',
        'source_name',
    ];
}
