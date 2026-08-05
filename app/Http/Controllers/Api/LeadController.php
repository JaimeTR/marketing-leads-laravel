<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadIndexRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Services\Leads\LeadService;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function __construct(private readonly LeadService $service)
    {
    }

    public function index(LeadIndexRequest $request): JsonResponse
    {
        return response()->json($this->service->list($request->validated()));
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        $lead = $this->service->create($request->validated());

        return response()->json($lead->load('source'), 201);
    }

    public function dashboard(): JsonResponse
    {
        return response()->json($this->service->dashboard());
    }
}
