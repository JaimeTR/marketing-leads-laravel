# 07 - Rendimiento

## Mejoras

- Paginacion del listado.
- Eager loading para evitar N+1.
- Indices por email, campana y fecha.
- Separacion del analisis IA para moverlo a queue.

## Siguiente paso

Para volumen alto, mover `LeadAiAnalyzer` a un job:

```text
LeadCreated -> AnalyzeLeadJob -> AI Provider -> Update Lead
```

Redis puede cachear metricas e insights del dashboard por periodos cortos.
