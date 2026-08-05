import { Head } from '@inertiajs/react';
import { Sparkles } from 'lucide-react';

export default function AppLayout({ title = 'Marketing Leads Next', children }) {
    return (
        <>
            <Head title={title} />
            <div className="flex min-h-screen flex-col">
                <header className="border-b border-slate-200 bg-white/80 backdrop-blur">
                    <div className="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                        <div className="flex items-center gap-3">
                            <span className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-600 to-violet-600 text-white shadow">
                                <Sparkles size={20} />
                            </span>
                            <div>
                                <p className="text-xs font-semibold uppercase tracking-wide text-sky-700">
                                    Propuesta de evolucion tecnologica
                                </p>
                                <h1 className="text-xl font-bold text-slate-950">Marketing Leads Next</h1>
                            </div>
                        </div>
                        <span className="hidden items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200 sm:inline-flex">
                            <span className="h-2 w-2 rounded-full bg-emerald-500" />
                            Laravel + IA
                        </span>
                    </div>
                </header>
                <main className="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 lg:px-8">{children}</main>
                <footer className="border-t border-slate-200 bg-white py-4">
                    <div className="mx-auto w-full max-w-7xl px-4 text-center text-xs text-slate-400 sm:px-6 lg:px-8">
                        Marketing Leads Next - Scoring y clasificacion de leads con IA intercambiable.
                    </div>
                </footer>
            </div>
        </>
    );
}