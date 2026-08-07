<?php

use App\DTOs\Website\CreateWebsiteData;
use App\DTOs\Website\UpdateWebsiteData;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Website;
use App\Services\WebsiteService;
use App\Support\RequestAuditContext;

/**
 * Proves the Auditable trait's automatic create/update/delete trail works
 * end to end (model event -> ModelAudited -> RecordModelAuditLog -> DB row)
 * when driven purely through a Service call — no HTTP request, no
 * controller, no middleware involved — confirming the mechanism doesn't
 * secretly depend on anything from the HTTP layer.
 */
it('records create, update and delete audit entries for writes made through a service, with no HTTP request at all', function () {
    $creator = User::factory()->create();
    $this->actingAs($creator);
    $service = app(WebsiteService::class);

    $website = $service->create($creator, new CreateWebsiteData(
        name: 'Integration Co',
        domain: 'integration.test',
        subdomain: '',
        locale: 'pt-BR',
        timezone: null,
    ));

    $createLog = AuditLog::where('action', 'create')
        ->where('auditable_type', Website::class)
        ->where('auditable_id', $website->id)
        ->sole();
    expect($createLog->new_values['domain'])->toBe('integration.test');
    // No middleware ran, so there is no request-scoped ip/user-agent to capture.
    expect($createLog->ip_address)->toBeNull();

    $service->update($website, new UpdateWebsiteData(name: 'Renamed Co'));

    $updateLog = AuditLog::where('action', 'update')
        ->where('auditable_id', $website->id)
        ->sole();
    expect($updateLog->old_values['name'])->toBe('Integration Co');
    expect($updateLog->new_values['name'])->toBe('Renamed Co');

    $service->delete($website->fresh());

    $deleteLog = AuditLog::where('action', 'delete')
        ->where('auditable_id', $website->id)
        ->sole();
    expect($deleteLog->old_values['domain'])->toBe('integration.test');
});

it('captures ip/user-agent on the audit entry once RequestAuditContext has been filled, exactly as the middleware would fill it', function () {
    app(RequestAuditContext::class)->fill('198.51.100.7', 'IntegrationAgent/1.0');

    $website = Website::factory()->create();

    $log = AuditLog::where('action', 'create')->where('auditable_id', $website->id)->sole();

    expect($log->ip_address)->toBe('198.51.100.7');
    expect($log->user_agent)->toBe('IntegrationAgent/1.0');
});
