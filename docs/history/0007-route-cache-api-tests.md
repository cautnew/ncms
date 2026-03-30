# 0007-route-cache-api-tests

## Objetivo
Garantir a integridade e segurança dos novos endpoints de API para gestão do cache de rotas através de testes automatizados de feature. Os testes cobrem autenticação obrigatória via Sanctum e as ações de consulta de status, geração e limpeza do cache utilizando mocks para o serviço subjacente.

## Arquivos criados
- `tests/Feature/Admin/Routes/RouteCacheApiTest.php`

## Arquivos alterados
- `N/A`

## Endpoints cobertos por testes
- `GET /api/admin/routes/cache`
- `POST /api/admin/routes/cache`
- `DELETE /api/admin/routes/cache`

## Observações de performance/segurança
- Testes de acesso não autorizado (HTTP 401).
- Validação da estrutura JSON de retorno (status e mensagens de sucesso).
- Isolamento dos testes utilizando Mockery para evitar efeitos colaterais no filesystem do ambiente de desenvolvimento.
