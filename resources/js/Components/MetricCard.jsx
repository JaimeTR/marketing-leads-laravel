export default function MetricCard({ label, value, tone = 'sky', icon: Icon }) {
    const tones = {
        sky: 'bg-sky-50 text-sky-700 ring-sky-200',
        emerald: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        amber: 'bg-amber-50 text-amber-700 ring-amber-200',
        slate: 'bg-slate-100 text-slate-700 ring-slate-200',
    };

    return (
        <div className="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div className="flex items-center justify-between">
                <p className="text-sm font-medium text-slate-500">{label}</p>
                {Icon && (
                    <span className={`rounded-lg p-2 ring-1 ${tones[tone]}`}>
                        <Icon size={18} />
                    </span>
                )}
            </div>
            <p className={`mt-3 inline-flex rounded-lg px-3 py-1 text-2xl font-bold ring-1 ${tones[tone]}`}>
                {value ?? 0}
            </p>
        </div>
    );
}