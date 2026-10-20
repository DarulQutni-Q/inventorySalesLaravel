<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $role = 'admin', string $email = 'admin@test.com'): User
    {
        return User::create([
            'name'     => ucfirst($role) . ' Test',
            'email'    => $email,
            'password' => Hash::make('password'),
            'role'     => $role,
        ]);
    }

    /** Tamu (belum login) diarahkan ke /login saat mengakses /dashboard. */
    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /** Password salah → response mengandung error. */
    public function test_wrong_password_rejected(): void
    {
        $this->createUser('admin', 'admin@test.com');

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }

    /** User dengan role staff tidak boleh mengakses rute admin (/users). */
    public function test_staff_cannot_access_admin_routes(): void
    {
        $staff = $this->createUser('staff', 'staff@test.com');

        $response = $this->actingAs($staff)->get('/users');

        // Middleware admin harus memblokir: 302 redirect atau 403 forbidden.
        $this->assertContains($response->getStatusCode(), [302, 403]);
    }

    /** Admin dapat mengakses /dashboard dengan status 200. */
    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createUser('admin', 'admin@test.com');

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
    }
}
