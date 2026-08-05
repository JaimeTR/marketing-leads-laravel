# 04 - Arquitectura

```mermaid
flowchart TD
    A["React + Inertia"] --> B["LeadController"]
    B --> C["LeadService"]
    C --> D["LeadRepositoryContract"]
    D --> E["EloquentLeadRepository"]
    E --> F["MySQL"]
    C --> G["LeadAiAnalyzerContract"]
    G --> H["Groq/OpenAI/Gemini"]
    G --> I["Fallback Scoring"]
```

## Capas

- UI: React + Tailwind.
- HTTP: Controllers y Form Requests.
- Business: Services.
- Data: Repositories y Eloquent Models.
- AI: contratos, prompts, providers y parser.
- Persistence: MySQL.

## Escalabilidad

La arquitectura permite mover el analisis IA a queues, cachear dashboard y versionar API sin cambiar la UI.
