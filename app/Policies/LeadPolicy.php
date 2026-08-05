<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(?User $user = null): bool
    {
        return true;
    }

    public function create(?User $user = null): bool
    {
        return true;
    }

    public function view(?User $user, Lead $lead): bool
    {
        return true;
    }
}
