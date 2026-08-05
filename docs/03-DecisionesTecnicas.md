# 03 - Decisiones Tecnicas

- Laravel 12 se propone por productividad, seguridad integrada y ecosistema.
- Inertia permite frontend React sin construir una API SPA completa desde cero.
- Tailwind facilita consistencia visual y velocidad de iteracion.
- Repository Pattern encapsula filtros y queries.
- Service Layer concentra reglas de negocio e IA.
- Form Requests separan validacion del controlador.
- Groq se usa como proveedor principal por velocidad y compatibilidad OpenAI.
- OpenAI y Gemini quedan detras de la misma interfaz para evitar dependencia de un proveedor.
- El scoring local de respaldo evita que la aplicacion falle si no hay API key.
