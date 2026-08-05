# 06 - Seguridad

## Implementado en la propuesta

- Validacion con Form Requests.
- Eloquent/Query Builder para consultas parametrizadas.
- Indice unico por email.
- Variables de entorno para API keys.
- Policies preparadas para autorizacion.
- React escaping por defecto.

## Recomendado para produccion

- Autenticacion obligatoria.
- Rate limiting para API.
- CSRF habilitado en rutas web.
- Logs sin datos sensibles.
- Rotacion de API keys.
- Permisos por rol comercial, admin y auditor.
