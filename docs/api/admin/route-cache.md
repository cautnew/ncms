## API: Admin Route Cache

Gerenciamento do cache de rotas via requisições REST JSON, protegido por autenticação.

### Autenticação
✅ Bearer Token (Sanctum) - Auth Required

---

### GET /api/admin/routes/cache
Retorna o status atual do cache de rotas.

**Response 200:**
```json
{
  "is_cached": true,
  "last_cached_at": "26/03/2026 21:58:12"
}
```

---

### POST /api/admin/routes/cache
Gera o cache de rotas compilado.

**Response 200:**
```json
{
  "message": "Route cache generated successfully.",
  "last_cached_at": "26/03/2026 22:00:00"
}
```

**Exemplo Curl:**
```bash
curl -X POST /api/admin/routes/cache \
-H "Authorization: Bearer YOUR_TOKEN" \
-H "Accept: application/json"
```

---

### DELETE /api/admin/routes/cache
Limpa o arquivo de cache de rotas existente.

**Response 200:**
```json
{
  "message": "Route cache cleared successfully."
}
```

**Exemplo Curl:**
```bash
curl -X DELETE /api/admin/routes/cache \
-H "Authorization: Bearer YOUR_TOKEN" \
-H "Accept: application/json"
```

---

### Observações Técnicas
Estes endpoints utilizam o `App\Services\Routes\RouteCacheService` para garantir consistência operacional entre a Web (Inertia) e a API (REST).
