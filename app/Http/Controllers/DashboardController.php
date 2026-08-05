<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Leads\LeadService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly LeadService $service)
    {
    }

    public function index(): Response
    {
        $dashboard = $this->service->dashboard();

        return Inertia::render('Dashboard/Index', [
            'metrics' => $dashboard['metrics'],
            'insights' => $dashboard['insights'],
            'predictions' => $dashboard['predictions'],
            'recent' => $dashboard['recent'],
        ]);
    }
}
