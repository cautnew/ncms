# 0006-route-cache-api

## Objetivo
Disponibilizar endpoints de API REST puros para a gestão do cache de rotas, permitindo que ferramentas externas ou o painel administrativo (via fetch/axios direto) possam monitorar e disparar a geração/limpeza do cache de forma autenticada.

## Arquivos criados
- `app/Http/Controllers/Api/Admin/RouteCacheController.php`
- `docs/api/admin/route-cache.md`

## Arquivos alterados
- `routes/api.php`

## Endpoints adicionados/alterados
- `GET /api/admin/routes/cache`
- `POST /api/admin/routes/cache`
- `DELETE /api/admin/routes/cache`

## Observações de performance/segurança
- Proteção via middleware `auth:sanctum`.
- Respostas puramente em JSON, desacopladas da lógica de redirecionamento de sessão web.
