<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LeadIndexRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Services\Leads\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LeadController extends Controller
{
    public function __construct(private readonly LeadService $service)
    {
    }

    public function index(LeadIndexRequest $request): Response
    {
        $filters = $request->validated();
        $dashboard = $this->service->dashboard();

        return Inertia::render('Leads/Index', [
            'filters' => $filters,
            'leads' => $this->service->list($filters),
            'metrics' => $dashboard['metrics'],
            'insights' => $dashboard['insights'],
            'campaigns' => $dashboard['metrics']['by_campaign']->pluck('campaign')->values(),
        ]);
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $duplicates = $this->service->checkDuplicates($data);

        $this->service->create($data);

        return back()->with(
            $duplicates->isEmpty() ? 'success' : 'warning',
            $duplicates->isEmpty()
                ? 'Lead registrado y analizado correctamente.'
                : 'Lead registrado, pero se detectaron posibles duplicados.'
        );
    }

    public function checkDuplicate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email:rfc', 'max:120'],
            'company' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        return response()->json(['duplicates' => $this->service->checkDuplicates($data)]);
    }

    public function duplicates(): JsonResponse
    {
        return response()->json(['groups' => $this->service->duplicates()]);
    }

    public function import(Request $request): RedirectResponse
    {
        $rows = $request->input('rows', []);

        if (!is_array($rows) || $rows === []) {
            return back()->with('error', 'No se recibieron filas para importar.');
        }

        $result = $this->service->import($rows);

        return back()->with('success', sprintf(
            'Importacion completada: %d creados, %d con errores.',
            $result['created'],
            count($result['errors'])
        ));
    }
}
