<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\LeadRepositoryContract;
use App\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentLeadRepository implements LeadRepositoryContract
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return Lead::query()
            ->with('source')
            ->when($filters['campaign'] ?? null, fn (Builder $query, string $campaign) => $query->where('campaign', $campaign))
            ->when($filters['from'] ?? null, fn (Builder $query, string $from) => $query->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn (Builder $query, string $to) => $query->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(12)
            ->withQueryString();
    }

    public function create(array $data): Lead
    {
        return Lead::query()->create($data);
    }

    public function createMany(array $rows): int
    {
        if ($rows === []) {
            return 0;
        }

        return Lead::query()->insert($rows);
    }

    public function potentialDuplicates(array $data, ?int $excludeId = null): Collection
    {
        $email = mb_strtolower(trim((string) ($data['email'] ?? '')));
        $name = $this->normalizeName($data['name'] ?? '');
        $company = $this->normalizeText($data['company'] ?? '');
        $phone = $this->normalizePhone($data['phone'] ?? '');

        return Lead::query()
            ->select('id', 'name', 'email', 'company', 'phone', 'campaign', 'created_at')
            ->where(function (Builder $query) use ($email, $name, $company, $phone): void {
                if ($email !== '') {
                    $query->orWhereRaw('LOWER(email) = ?', [$email]);
                }

                if ($name !== '') {
                    if ($company !== '') {
                        $query->orWhere(function (Builder $inner) use ($name, $company): void {
                            $inner->whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [$name])
                                ->whereRaw('LOWER(REPLACE(COALESCE(company, ""), " ", "")) = ?', [$company]);
                        });
                    }

                    if ($phone !== '') {
                        $query->orWhere(function (Builder $inner) use ($name, $phone): void {
                            $inner->whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [$name])
                                ->whereRaw('REPLACE(REPLACE(REPLACE(COALESCE(phone, ""), " ", ""), "-", ""), ".", "") = ?', [$phone]);
                        });
                    }
                }
            })
            ->when($excludeId !== null, fn (Builder $query) => $query->whereKeyNot($excludeId))
            ->limit(10)
            ->get();
    }

    public function groupedDuplicates(): Collection
    {
        $leads = Lead::query()->select('id', 'name', 'email', 'company', 'phone', 'campaign', 'created_at')->get();

        return $leads
            ->groupBy(function (Lead $lead): string {
                $name = $this->normalizeName($lead->name);
                $company = $this->normalizeText($lead->company);
                $phone = $this->normalizePhone($lead->phone);

                if ($name !== '' && $company !== '' && $phone !== '') {
                    return 'ncp:' . md5($name . '|' . $company . '|' . $phone);
                }

                return 'email:' . mb_strtolower(trim((string) $lead->email));
            })
            ->filter(fn (Collection $group) => $group->count() > 1)
            ->values();
    }

    private function normalizeName(mixed $value): string
    {
        return mb_strtolower(preg_replace('/\s+/', '', trim((string) $value)) ?? '');
    }

    private function normalizeText(mixed $value): string
    {
        return mb_strtolower(preg_replace('/\s+/', '', trim((string) $value)) ?? '');
    }

    private function normalizePhone(mixed $value): string
    {
        return preg_replace('/[^0-9]/', '', trim((string) $value)) ?? '';
    }

    public function metrics(): array
    {
        $total = Lead::query()->count();
        $byCampaign = Lead::query()
            ->select('campaign', DB::raw('COUNT(*) as total'))
            ->groupBy('campaign')
            ->orderByDesc('total')
            ->get();

        return [
            'total' => $total,
            'hot' => Lead::query()->where('ai_temperature', 'hot')->count(),
            'warm' => Lead::query()->where('ai_temperature', 'warm')->count(),
            'cold' => Lead::query()->where('ai_temperature', 'cold')->count(),
            'average_score' => round((float) Lead::query()->avg('ai_score'), 1),
            'by_campaign' => $byCampaign,
        ];
    }

    public function recentForAnalysis(int $limit = 50): Collection
    {
        return Lead::query()
            ->with('source')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
