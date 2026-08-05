# 08 - Technical Review

## SQL Injection

Problema: el sistema original concatenaba input del usuario en SQL.

Impacto: permitia manipular consultas.

Solucion: Laravel Eloquent y Form Requests.

Resultado: mitigacion del riesgo mediante consultas parametrizadas.

## N+1 Query

Problema: se consultaba el origen de campana por cada lead.

Impacto: degradacion al crecer el volumen.

Solucion: relacion Eloquent y eager loading.

Resultado: consultas predecibles y escalables.

## IA dependiente de proveedor unico

Problema: integrar un solo proveedor genera lock-in.

Impacto: costos, latencia o caidas afectan el producto.

Solucion: contrato `LeadAiAnalyzerContract` y clientes intercambiables.

Resultado: Groq, OpenAI y Gemini pueden cambiarse por configuracion.

## Falta de vision de producto

Problema: el modulo original solo registraba y listaba datos.

Impacto: bajo valor para marketing y ventas.

Solucion: scoring, clasificacion, resumen y recomendaciones IA.

Resultado: el modulo evoluciona hacia una herramienta de priorizacion comercial.
