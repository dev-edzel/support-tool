<?php

namespace Tests\Unit;

use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_get_categories(): void
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
    }

    public function test_store_ip_address(): void
    {
        $data = ['ip_address' => '255.255.255.255'];

        $response = $this->post('/api/ip-addresses', $data);
        $response->assertStatus(200);
    }
}
