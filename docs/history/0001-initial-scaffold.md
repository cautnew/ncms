# 0001-initial-scaffold

## Objetivo
Criar o MVP do Kautch CMS focado em performance, API First, regras avançadas de SEO e Templates PHTML, além do controle obrigatório de auditoria e revisão de conteúdo.

## Arquivos criados
- `database/migrations/2026_03_26_000001_create_kautch_cms_tables.php`
- `app/Models/Page.php`
- `app/Models/ContentRevision.php`
- `app/Models/AdminActivityLog.php`
- `app/Observers/PageObserver.php`
- `app/Templates/Contracts/TemplateRendererInterface.php`
- `app/Http/Controllers/Api/Admin/TemplateValidationController.php`
- `tests/Feature/Admin/Templates/TemplateValidationTest.php`
- `docs/api/admin/templates.md`
- `routes/api.php`

## Arquivos alterados
- `N/A`

## Migrations criadas
- `2026_03_26_000001_create_kautch_cms_tables.php`

## Tabelas novas
1. **pages**: id, title, slug (index), status, published_at, content (json), head_tags (json), body_start_tags (json), body_end_tags (json), schema_jsonld (json), template_class, template_payload (json)
2. **content_revisions**: id, content_type, content_id (index), user_id (index), action, title, slug, status, payload (json), created_at (index)
3. **admin_activity_logs**: id, user_id (index), action, entity_type, entity_id (index), meta (json), created_at (index)

## Campos novos em tabelas existentes
- `N/A`

## Endpoints adicionados/alterados
- `GET /api/admin/templates/validate-class`

## Observações de performance/segurança
- A tabela `pages` contém os campos cruciais (slug, status, published_at) sob índices para garantir queries rápidas via API pública (where status=published and published_at<=now).
- As tags injáveis de SEO (head_tags, body_start_tags, body_end_tags) devem ter seu HTML sanitizado caso permitam customizações perigosas. Seu output será renderizado no app frontend/PHTML.
- O Observer envia as queries de `admin_activity_logs` e `content_revisions` apenas no saved/deleted events.
- Todos os endpoints protegidos pelo middleware `auth:sanctum`.