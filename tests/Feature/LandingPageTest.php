<?php

it('renders the coming soon landing page with the expected message', function () {
    config(['session.driver' => 'array']);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('Este CMS está sendo preparado')
        ->assertSee('muita facilidade')
        ->assertSee('simplicidade')
        ->assertSee('autonomia');
});
