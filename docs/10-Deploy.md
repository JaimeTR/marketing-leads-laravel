# 10 - Deploy

## Railway o Render

1. Crear servicio PHP/Laravel.
2. Configurar variables `.env`.
3. Agregar MySQL administrado.
4. Ejecutar migraciones.
5. Ejecutar build de Vite.

## Hostinger/VPS sin Docker

1. Subir codigo al servidor.
2. Ejecutar `composer install --no-dev --optimize-autoloader`.
3. Ejecutar `npm ci && npm run build`.
4. Configurar `.env`.
5. Apuntar document root a `public/`.
6. Ejecutar:

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
