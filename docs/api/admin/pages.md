## API: Admin Pages

Endpoints de CRUD para a entidade `Page` dentro da área logada do Kautch CMS.

### Autenticação
✅ Bearer Token (Sanctum) - Auth Required

---

### GET /api/admin/pages
Retorna a listagem paginada de páginas criadas com busca opcional.

**Query Params:**
- `per_page` (int) opcional: limite por página. Default 15.
- `search` (string) opcional: busca em title e slug.

**Response 200:**
Paginator layout resource padrão do Laravel (data com array de objects, fields index_page, metadata da paginação).

---

### POST /api/admin/pages
Cria uma nova página (Draft, Published, Archived).

**Body Payload:**
| Nome | Tipo | Opcional | Descrição |
|---|---|---|---|
| title | string | Não | Título da página |
| slug | string | Não | Slug única de acesso |
| status | string | Não | draft, published, ou archived |
| content | array/json | Sim | Json com a renderização de blocos do React |
| metadados e templates | diversificado | Sim | Campos permitidos para injeção via regras |

**Request Exemplo:**
```bash
curl -X POST /api/admin/pages \
-H "Authorization: Bearer TOKEN" \
-d '{"title": "Home", "slug": "home", "status": "draft"}'
```

---

### GET /api/admin/pages/{id}
Recupera as informações e metadata/json payload completos de uma única página específica.

**Response 200:** Instância do record retornada no resource `data`.

---

### PUT/PATCH /api/admin/pages/{id}
Atualiza atributos específicos de uma página existente. 

**Path Params:**
- `id` : ID da page.

---

### DELETE /api/admin/pages/{id}
Deleta um registro no banco de dados.

**Atenção:** Os Action Logs do System Observer registram o evento na tabela `admin_activity_logs`. A revisão é descartada do banco após SoftDelete/Delete dependendo da política.

---

### Testes Relacionados
`tests/Feature/Admin/Pages/PageCrudTest.php`
