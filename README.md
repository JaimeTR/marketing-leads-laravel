# Marketing Leads Next

Proyecto 2 - Propuesta de evolucion tecnologica del modulo de leads con integracion IA

## Enfoque

Este repositorio no reemplaza la entrega oficial en PHP nativo. Es una propuesta de evolucion tecnica para mostrar como el modulo podria crecer hacia una arquitectura moderna, mantenible, testeable y preparada para deploy.

Stack propuesto:

- Laravel 12.
- MySQL.
- Inertia.
- React.
- Tailwind.
- Vite.
- REST API.
- Repository Pattern.
- Service Layer.
- Form Requests.
- Policies.
- Pest/PHPUnit.
- IA con Groq por defecto y OpenAI/Gemini como proveedores intercambiables.

## Importante

Esta carpeta contiene el codigo especifico de la aplicacion como overlay para un Laravel 12 nuevo. No incluye `vendor/`, `node_modules` ni el esqueleto completo generado por Composer.

## Instalacion sobre Laravel 12

```bash
composer create-project laravel/laravel marketing-leads-next
cd marketing-leads-next
composer require inertiajs/inertia-laravel guzzlehttp/guzzle
composer require pestphp/pest pestphp/pest-plugin-laravel --dev
npm install @inertiajs/react react react-dom @vitejs/plugin-react lucide-react axios laravel-vite-plugin tailwindcss postcss autoprefixer vite
```

Luego copiar el contenido de este overlay encima del proyecto Laravel nuevo.

Archivos especiales:

- Copiar `bootstrap/providers.overlay.php` sobre `bootstrap/providers.php` o agregar `MarketingLeadsServiceProvider` manualmente.
- Copiar `bootstrap/app.overlay.php` sobre `bootstrap/app.php` o agregar `HandleInertiaRequests` al middleware web.
- Usar `composer.overlay.json` y `package.overlay.json` como referencia para dependencias (el listado del comando `npm install` ya incluye todo lo necesario: `axios` y `laravel-vite-plugin` son requeridos por `vite.config.js` y `resources/js/bootstrap.js`).

Inicializar Pest para que los tests del overlay funcionen (genera `tests/Pest.php` y `tests/TestCase.php`):

```bash
./vendor/bin/pest --init
```

## Configuracion

Copiar `.env.example` como `.env` y configurar. Importante: el `.env` generado por `composer create-project` ya trae un `APP_KEY` valido; usa el `.env.example` solo como referencia para las variables de base de datos e IA (no lo copies por encima o perderas el `APP_KEY`).

```env
AI_PROVIDER=groq
GROQ_API_KEY=...
```

Si no se define API key, la aplicacion usa scoring local de respaldo.

## Ejecucion

```bash
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Abrir:

```text
http://localhost:8000/leads
```

## API

```text
GET /api/leads
POST /api/leads
GET /api/leads/dashboard
```

## IA

Casos incluidos:

- Lead Score IA.
- Clasificacion frio/tibio/caliente.
- Recomendacion de siguiente accion.
- Resumen ejecutivo del dashboard.
- Tendencias, riesgos y recomendaciones.
- Deteccion inteligente futura por nombre, empresa y telefono.

## Documentacion

- [Problemas encontrados](docs/01-ProblemasEncontrados.md)
- [Soluciones](docs/02-Soluciones.md)
- [Decisiones tecnicas](docs/03-DecisionesTecnicas.md)
- [Arquitectura](docs/04-Arquitectura.md)
- [Mejoras futuras](docs/05-MejorasFuturas.md)
- [Seguridad](docs/06-Seguridad.md)
- [Rendimiento](docs/07-Rendimiento.md)
- [Technical Review](docs/08-TechnicalReview.md)
- [Roadmap](docs/09-Roadmap.md)
- [Deploy](docs/10-Deploy.md)
- [OpenAPI](docs/openapi.yaml)

## Autor

Jaime Tarazona.
