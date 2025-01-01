<?php

test('demo test', function () {
    $response = $this->get('/api/channel');
    $response->assertStatus(200);
});
