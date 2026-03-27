# 0005-route-cache-service

## Objetivo
Refatorar a lógica de geração e limpeza do cache de rotas para uma camada de serviço (`RouteCacheService`), seguindo as melhores práticas de Clean Architecture e separação de responsabilidades. A API (controller) agora consome este serviço para realizar as operações de cache.

## Arquivos criados
- `app/Services/Routes/RouteCacheService.php`

## Arquivos alterados
- `app/Http/Controllers/Routes/RoutesController.php`

## Endpoints adicionados/alterados
- `GET /routes` (agora usa o serviço para pegar a data do cache)
- `GET /routes/cache` (agora usa o serviço para o status detalhado)
- `POST /routes/cache` (gera o cache via serviço)
- `DELETE /routes/cache` (limpa o cache via serviço)

## Observações de performance/segurança
A lógica de manipulação de arquivos e chamadas de comando Artisan foi centralizada no serviço, facilitando a manutenção e possíveis integrações futuras com filas (queues) ou outros drivers de cache.
