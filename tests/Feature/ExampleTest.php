<?php

test('the application redirects to login', function () {
    $this->get('/')
        ->assertRedirect(route('login'));
});
