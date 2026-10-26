<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        $response = $this->from('/admin/dashboard')->post('/login/admin', [
            'name' => 'admin',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_dokter_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'dokter',
            'email' => 'dokter@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'dokter',
        ]);

        $this->from('/admin/dashboard')->post('/login/dokter', [
            'name' => 'dokter',
            'password' => '12345678',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_perawat_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'perawat',
            'email' => 'perawat@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'perawat',
        ]);

        $this->from('/admin/dashboard')->post('/login/perawat', [
            'name' => 'perawat',
            'password' => '12345678',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }
}
