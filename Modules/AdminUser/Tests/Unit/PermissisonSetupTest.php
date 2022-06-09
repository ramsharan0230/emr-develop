<?php

namespace Modules\AdminUser\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissisonSetupTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /**
     * unit test wheather permession setup is working or not
     * @return assertStatus
     */
    public function test_permission_setup_store()
    {
        $response = $this->post('/admin/user/groups/permission-store', [
            '_token' => csrf_token(),
            'name' => 'test XXX',
            'status' => true,
            'order_by' => 1
        ]);
        $response->assertStatus(200);
    }
}
