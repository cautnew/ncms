# 0003-routes-cache-ui

## Objetivo
Criar uma sessão na interface de rotas (`routes.tsx`) que permita visualizar a data em que o cache de rotas foi gerado e ofereça um botão de ação para engatilhar a recriação deste cache (utilizando `Artisan::call('route:cache')`).

## Arquivos alterados
- `resources/js/pages/routes/routes.tsx`
- `app/Http/Controllers/Routes/RoutesController.php`
- `routes/routes.php`

## Endpoints adicionados/alterados
- `POST /routes/cache`
