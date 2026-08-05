import { BrainCircuit, TrendingUp, Sparkles, ShieldAlert, Flame, Snowflake, Thermometer } from 'lucide-react';

const temperatureConfig = {
    hot: { label: 'Calientes', Icon: Flame, bar: 'bg-emerald-500', text: 'text-emerald-700' },
    warm: { label: 'Tibios', Icon: Thermometer, bar: 'bg-amber-500', text: 'text-amber-700' },
    cold: { label: 'Frios', Icon: Snowflake, bar: 'bg-sky-400', text: 'text-sky-700' },
};

export default function InsightPanel({ insights, metrics }) {
    const total = Number(metrics?.total ?? 0);

    function pct(value) {
        return total > 0 ? Math.round((Number(value) / total) * 100) : 0;
    }

    return (
        <section className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div className="border-b border-slate-100 bg-gradient-to-r from-violet-50 to-sky-50 px-6 py-5">
                <div className="flex items-start gap-3">
                    <span className="rounded-xl bg-violet-100 p-2.5 text-violet-700">
                        <BrainCircuit size={22} />
                    </span>
                    <div>
                        <h2 className="text-lg font-semibold text-slate-950">Dashboard IA</h2>
                        <p className="mt-0.5 text-sm leading-relaxed text-slate-600">{insights?.summary}</p>
                    </div>
                </div>

                {total > 0 && (
                    <div className="mt-4">
                        <div className="flex h-2.5 w-full overflow-hidden rounded-full bg-slate-200">
                            <div className="bg-emerald-500" style={{ width: `${pct(metrics.hot)}%` }} />
                            <div className="bg-amber-500" style={{ width: `${pct(metrics.warm)}%` }} />
                            <div className="bg-sky-400" style={{ width: `${pct(metrics.cold)}%` }} />
                        </div>
                        <div className="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-600">
                            {['hot', 'warm', 'cold'].map((key) => {
                                const cfg = temperatureConfig[key];
                                const { Icon } = cfg;
                                return (
                                    <span key={key} className="inline-flex items-center gap-1">
                                        <Icon size={13} className={cfg.text} />
                                        {cfg.label}: {metrics[key] ?? 0} ({pct(metrics[key])}%)
                                    </span>
                                );
                            })}
                        </div>
                    </div>
                )}
            </div>

            <div className="grid gap-4 p-6 md:grid-cols-3">
                <InsightList title="Tendencias" items={insights?.trends} Icon={TrendingUp} iconTone="text-sky-700 bg-sky-50" />
                <InsightList title="Recomendaciones" items={insights?.recommendations} Icon={Sparkles} iconTone="text-violet-700 bg-violet-50" />
                <InsightList title="Riesgos" items={insights?.risks} Icon={ShieldAlert} iconTone="text-rose-700 bg-rose-50" />
            </div>
        </section>
    );
}

function InsightList({ title, items = [], Icon, iconTone }) {
    const safeItems = Array.isArray(items) ? items.filter((item) => typeof item === 'string') : [];

    return (
        <div className="rounded-xl border border-slate-100 bg-slate-50/60 p-4">
            <div className="flex items-center gap-2">
                {Icon && <span className={`rounded-md p-1.5 ${iconTone}`}><Icon size={15} /></span>}
                <h3 className="text-sm font-semibold text-slate-800">{title}</h3>
            </div>
            <ul className="mt-3 space-y-2 text-sm text-slate-600">
                {(safeItems.length ? safeItems : ['Sin datos suficientes.']).map((item) => (
                    <li key={item} className="rounded-lg bg-white px-3 py-2 ring-1 ring-slate-100">
                        {item}
                    </li>
                ))}
            </ul>
        </div>
    );
}