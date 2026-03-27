# 0004-routes-cache-details

## Objetivo
Ampliar as opções de gerenciamento de cache de rotas criando uma página detalhada dedicada. Além de fornecer a possibilidade de gerar o cache (como antes), introduzir uma visualização granular que inclui informações explicativas, o status ativo com data e botão para a ação explícita de *Limpar Cache*, se este já estiver gerado. Na listagem principal de rotas, a notificação passou a ser dispensável e oferece atalho para a nova tela.

## Arquivos criados
- `resources/js/pages/routes/cache.tsx`

## Arquivos alterados
- `resources/js/pages/routes/routes.tsx`
- `app/Http/Controllers/Routes/RoutesController.php`
- `routes/routes.php`

## Endpoints adicionados/alterados
- `GET /routes/cache`
- `DELETE /routes/cache`

## Observações de performance/segunrança
A implementação continua restrita ao ambiente autenticado, blindando a ação administrativa de esgotamento/falsificação de caches. O novo component UI protege contra acidentes através de validações do lado do cliente (window.confirm) antes do acionamento real do route:clear.
