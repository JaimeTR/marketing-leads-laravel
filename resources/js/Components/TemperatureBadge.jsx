import { Flame, Thermometer, Snowflake, HelpCircle } from 'lucide-react';

const config = {
    hot: { label: 'Caliente', classes: 'bg-emerald-50 text-emerald-700 ring-emerald-200', Icon: Flame },
    warm: { label: 'Tibio', classes: 'bg-amber-50 text-amber-700 ring-amber-200', Icon: Thermometer },
    cold: { label: 'Frio', classes: 'bg-sky-50 text-sky-700 ring-sky-200', Icon: Snowflake },
};

export default function TemperatureBadge({ value }) {
    const cfg = config[value] ?? { label: 'Sin clasificar', classes: 'bg-slate-100 text-slate-500 ring-slate-200', Icon: HelpCircle };
    const { label, classes, Icon } = cfg;

    return (
        <span className={`inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ${classes}`}>
            <Icon size={12} />
            {label}
        </span>
    );
}

export function scoreTone(score) {
    if (score >= 75) return 'text-emerald-700 bg-emerald-50 ring-emerald-200';
    if (score >= 50) return 'text-amber-700 bg-amber-50 ring-amber-200';
    return 'text-slate-700 bg-slate-100 ring-slate-200';
}