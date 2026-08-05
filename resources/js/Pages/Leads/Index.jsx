import { useState } from 'react';
import { Link, router, useForm, usePage } from '@inertiajs/react';
import { Filter, Plus, Search, Users, Flame, Thermometer, Gauge, Building2, X, Sparkles } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import InsightPanel from '@/Components/InsightPanel';
import MetricCard from '@/Components/MetricCard';
import TemperatureBadge, { scoreTone } from '@/Components/TemperatureBadge';

export default function Index({ leads, filters, metrics, insights, campaigns }) {
    const { flash = {} } = usePage().props;
    const [dismissed, setDismissed] = useState(false);

    const form = useForm({
        name: '',
        email: '',
        company: '',
        phone: '',
        campaign: '',
    });

    const filterForm = useForm({
        campaign: filters?.campaign ?? '',
        from: filters?.from ?? '',
        to: filters?.to ?? '',
    });

    function submitLead(event) {
        event.preventDefault();
        form.post('/leads', { preserveScroll: true, onSuccess: () => form.reset() });
    }

    function submitFilters(event) {
        event.preventDefault();
        router.get('/leads', filterForm.data, { preserveState: true });
    }

    return (
        <AppLayout title="Leads">
            <div className="space-y-6">
                {flash.success && !dismissed && (
                    <div className="flex items-start justify-between gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <span>{flash.success}</span>
                        <button onClick={() => setDismissed(true)} className="rounded p-1 hover:bg-emerald-100" aria-label="Cerrar aviso">
                            <X size={16} />
                        </button>
                    </div>
                )}

                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <MetricCard label="Leads totales" value={metrics.total} tone="sky" icon={Users} />
                    <MetricCard label="Leads calientes" value={metrics.hot} tone="emerald" icon={Flame} />
                    <MetricCard label="Leads tibios" value={metrics.warm} tone="amber" icon={Thermometer} />
                    <MetricCard label="Score promedio" value={metrics.average_score} tone="slate" icon={Gauge} />
                </div>

                <InsightPanel insights={insights} metrics={metrics} />

                <div className="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
                    <section className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div className="mb-5 flex items-center gap-2">
                            <span className="rounded-lg bg-sky-50 p-2 text-sky-700">
                                <Plus size={18} />
                            </span>
                            <h2 className="text-lg font-semibold text-slate-950">Registrar lead</h2>
                        </div>

                        <form onSubmit={submitLead} className="space-y-4">
                            <Field label="Nombre" error={form.errors.name} required>
                                <input className="focus-ring w-full rounded-lg border border-slate-300 px-3 py-2" value={form.data.name} onChange={(e) => form.setData('name', e.target.value)} />
                            </Field>
                            <Field label="Email" error={form.errors.email} required>
                                <input type="email" className="focus-ring w-full rounded-lg border border-slate-300 px-3 py-2" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} />
                            </Field>
                            <Field label="Empresa" error={form.errors.company}>
                                <input className="focus-ring w-full rounded-lg border border-slate-300 px-3 py-2" value={form.data.company} onChange={(e) => form.setData('company', e.target.value)} />
                            </Field>
                            <Field label="Telefono" error={form.errors.phone}>
                                <input className="focus-ring w-full rounded-lg border border-slate-300 px-3 py-2" value={form.data.phone} onChange={(e) => form.setData('phone', e.target.value)} />
                            </Field>
                            <Field label="Campana" error={form.errors.campaign} required>
                                <input list="campaign-list" className="focus-ring w-full rounded-lg border border-slate-300 px-3 py-2" value={form.data.campaign} onChange={(e) => form.setData('campaign', e.target.value)} />
                            </Field>
                            <button disabled={form.processing} className="focus-ring inline-flex w-full items-center justify-center gap-2 rounded-lg bg-sky-700 px-4 py-2.5 font-semibold text-white transition hover:bg-sky-800 disabled:cursor-not-allowed disabled:opacity-60">
                                {form.processing ? (
                                    <>
                                        <span className="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" />
                                        Analizando con IA...
                                    </>
                                ) : (
                                    <>
                                        <Sparkles size={18} />
                                        Guardar y analizar
                                    </>
                                )}
                            </button>
                            <p className="flex items-center gap-1.5 text-xs text-slate-500">
                                <Sparkles size={13} className="text-violet-500" />
                                La IA clasifica el lead como frio, tibio o caliente y sugiere el siguiente paso.
                            </p>
                        </form>
                    </section>

                    <section className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div className="mb-5 flex items-center gap-2">
                            <span className="rounded-lg bg-slate-100 p-2 text-slate-700">
                                <Filter size={18} />
                            </span>
                            <h2 className="text-lg font-semibold text-slate-950">Filtros</h2>
                        </div>
                        <form onSubmit={submitFilters} className="mb-6 grid gap-4 md:grid-cols-4">
                            <input list="campaign-list" placeholder="Campana" className="focus-ring rounded-lg border border-slate-300 px-3 py-2" value={filterForm.data.campaign} onChange={(e) => filterForm.setData('campaign', e.target.value)} />
                            <input type="date" className="focus-ring rounded-lg border border-slate-300 px-3 py-2" value={filterForm.data.from} onChange={(e) => filterForm.setData('from', e.target.value)} />
                            <input type="date" className="focus-ring rounded-lg border border-slate-300 px-3 py-2" value={filterForm.data.to} onChange={(e) => filterForm.setData('to', e.target.value)} />
                            <button className="focus-ring inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white transition hover:bg-slate-800">
                                <Search size={18} />
                                Buscar
                            </button>
                        </form>

                        <datalist id="campaign-list">
                            {campaigns.map((campaign) => <option key={campaign} value={campaign} />)}
                        </datalist>

                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-slate-200">
                                <thead>
                                    <tr className="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        <th className="py-3 pr-4">Lead</th>
                                        <th className="py-3 pr-4">Campana</th>
                                        <th className="py-3 pr-4">Score</th>
                                        <th className="py-3 pr-4">Estado</th>
                                        <th className="py-3 pr-4">Proxima accion</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100 text-sm">
                                    {leads.data.map((lead) => (
                                        <tr key={lead.id} className="transition hover:bg-slate-50">
                                            <td className="py-4 pr-4">
                                                <div className="flex items-center gap-3">
                                                    <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-violet-500 text-xs font-bold text-white">
                                                        {initials(lead.name)}
                                                    </span>
                                                    <div>
                                                        <p className="font-medium text-slate-950">{lead.name}</p>
                                                        <p className="text-slate-500">{lead.email}</p>
                                                        {(lead.company || lead.phone) && (
                                                            <p className="mt-0.5 flex items-center gap-2 text-xs text-slate-400">
                                                                {lead.company && <span className="inline-flex items-center gap-1"><Building2 size={11} /> {lead.company}</span>}
                                                                {lead.phone && <span>{lead.phone}</span>}
                                                            </p>
                                                        )}
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="py-4 pr-4">
                                                <p className="text-slate-800">{lead.campaign}</p>
                                                <p className="text-slate-500">{lead.source?.source_name ?? 'Sin origen'}</p>
                                            </td>
                                            <td className="py-4 pr-4">
                                                <span className={`inline-flex rounded-full px-3 py-1 font-semibold ring-1 ${scoreTone(lead.ai_score)}`}>
                                                    {lead.ai_score ?? 0}%
                                                </span>
                                            </td>
                                            <td className="py-4 pr-4">
                                                <TemperatureBadge value={lead.ai_temperature} />
                                            </td>
                                            <td className="max-w-xs py-4 pr-4 text-slate-600">{lead.ai_recommended_action ?? 'Pendiente de analisis.'}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            {leads.data.length === 0 && (
                                <div className="py-12 text-center text-slate-500">
                                    <p className="text-lg font-medium">No hay leads</p>
                                    <p className="mt-1 text-sm">Prueba a ajustar los filtros o registrar un lead nuevo.</p>
                                </div>
                            )}
                        </div>

                        <div className="mt-5 flex flex-wrap gap-2">
                            {leads.links.map((link) => (
                                <Link key={link.label} href={link.url ?? '#'} preserveScroll className={`rounded-lg border px-3 py-1 text-sm transition ${link.active ? 'border-sky-700 bg-sky-700 text-white' : 'border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50'}`} dangerouslySetInnerHTML={{ __html: link.label }} />
                            ))}
                        </div>
                    </section>
                </div>
            </div>
        </AppLayout>
    );
}

function Field({ label, error, required = false, children }) {
    return (
        <label className="block">
            <span className="mb-1 block text-sm font-medium text-slate-700">
                {label} {required && <span className="text-rose-500">*</span>}
            </span>
            {children}
            {error && <span className="mt-1 block text-sm text-red-600">{error}</span>}
        </label>
    );
}

function initials(name) {
    return (name || '?')
        .trim()
        .split(/\s+/)
        .slice(0, 2)
        .map((word) => word[0]?.toUpperCase() ?? '')
        .join('');
}