# Kautch CMS — Histórico de Mudança
## Change ID: 0001
## Versão: v0.1.0
## Tipo: Initial Scaffold / MVP Base
## Data: YYYY-MM-DD

---

## 🎯 Objetivo da Mudança
Criação do scaffold inicial do **Kautch CMS**, incluindo backend Laravel 12 (API-first),
Admin em React + Tailwind, estrutura de SEO avançado, sistema de templates PHTML,
versionamento de conteúdo e auditoria administrativa.

---

## 🧱 Arquivos Criados
### Backend (Laravel)
- app/Models/Page.php
- app/Models/Post.php
- app/Models/ContentRevision.php
- app/Models/AdminActivityLog.php
- app/Observers/PageObserver.php
- app/Observers/PostObserver.php
- app/Templates/Contracts/TemplateRendererInterface.php
- app/Templates/LandingTemplate.php
- app/Http/Controllers/Api/Admin/PageController.php
- app/Http/Controllers/Api/Admin/TemplateValidationController.php
- app/Http/Controllers/Api/Public/PagePublicController.php
- app/Services/Seo/SeoBuilder.php
- routes/api.php

### Templates
- resources/templates/landing.phtml

### Frontend (Admin React)
- admin/src/pages/pages/PageEdit.tsx
- admin/src/components/TemplateClassValidator.tsx
- admin/src/components/RevisionsPanel.tsx

### Documentação
- docs/history/0001-initial-scaffold.md
- docs/history/manifest-0001.json

---

## ✏️ Arquivos Alterados
- config/sanctum.php
- app/Providers/AppServiceProvider.php
- database/seeders/DatabaseSeeder.php

---

## 🗄️ Migrations Criadas
- 2026_03_26_000001_create_pages_table.php
- 2026_03_26_000002_create_posts_table.php
- 2026_03_26_000003_create_content_revisions_table.php
- 2026_03_26_000004_create_admin_activity_logs_table.php

---

## 📊 Tabelas Criadas
### pages
- id
- title
- slug (unique, index)
- status (index)
- published_at (index)
- content (json)
- meta_title
- meta_description
- canonical_url
- robots
- head_tags (json)
- body_start_tags (json)
- body_end_tags (json)
- schema_jsonld (json)
- template_class
- template_payload (json)
- created_at / updated_at

### posts
- Estrutura similar à pages + excerpt

### content_revisions
- id
- content_type (page|post)
- content_id (index)
- user_id (index, nullable)
- action
- status
- payload (json)
- created_at

### admin_activity_logs
- id
- user_id (index)
- action
- entity_type
- entity_id (index, nullable)
- meta (json)
- created_at

---

## 🌐 Endpoints Adicionados
### Públicos
- GET /api/public/pages/{slug}
- GET /api/public/posts
- GET /api/public/posts/{slug}
- GET /api/public/seo/{type}/{slug}

### Admin
- POST /api/admin/auth/login
- GET /api/admin/pages
- POST /api/admin/pages
- PUT /api/admin/pages/{id}
- GET /api/admin/pages/{id}/revisions
- GET /api/admin/templates/validate-class
- GET /api/admin/activity-logs

---

## 🔍 Observações de Performance
- Índices criados em slug, status e published_at
- Revisions e logs só são gerados em eventos de persistência
- Conteúdo público preparado para cache por slug

---

## 🔐 Observações de Segurança
- Sanitização obrigatória para head_tags e body injections
- Endpoints admin protegidos por Sanctum
- Validação de classe de template via Reflection

---

## ✅ Checklist de Testes Manuais
- [ ] Criar página e salvar
- [ ] Editar página e verificar revision criada
- [ ] Validar template_class existente e inexistente
- [ ] Consumir página via endpoint público
- [ ] Conferir activity log após ações no admin