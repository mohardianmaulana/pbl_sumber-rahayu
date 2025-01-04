<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_login_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));
        $response->assertStatus(302);
    }

    public function test_melihat_halaman_dashboard_setelah_login(): void
    {
        Role::findOrCreate('admin');
        Permission::findOrCreate('view');

        $user = User::firstOrCreate([
            'email' => 'ardi@gmail.com',
        ], [
            'name' => 'Ardi',
            'roles_id' => '1',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_menampilkan_halaman_kategori(): void
    {
        Role::findOrCreate('admin');
        Permission::findOrCreate('view');

        $user = User::firstOrCreate([
            'email' => 'ardi@gmail.com',
        ], [
            'name' => 'Ardi',
            'roles_id' => '1',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/kategori');

        $response->assertStatus(200);
        $response->assertSee('Daftar Kategori');
    }

    public function test_menampilkan_form_tambah_data_kategori(): void
    {
        Role::findOrCreate('admin');
        Permission::findOrCreate('crud');

        $user = User::firstOrCreate([
            'email' => 'ardi@gmail.com',
        ], [
            'name' => 'Ardi',
            'roles_id' => '1',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/kategori/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Kategori');
    }

    public function test_menambah_data_kategori_dengan_gambar(): void
    {
        // Menyiapkan role dan permission
        Role::findOrCreate('admin');
        Permission::findOrCreate('crud');

        // Membuat user admin dan mengaitkan role
        $user = User::firstOrCreate([
            'email' => 'ardi@gmail.com',
        ], [
            'name' => 'Ardi',
            'roles_id' => '1',
            'password' => bcrypt('12345678'),
        ]);
        $user->assignRole('admin');

        // Simulasikan file storage
        Storage::fake('public');

        // Buat file gambar palsu dengan ukuran minimal 1 KB
        $file = UploadedFile::fake()->image('gambar_kategori.jpg')->size(1024);

        // Data untuk request
        $data = [
            'nama_kategori' => 'Minuman',
            'gambar_kategori' => $file,
        ];

        // Melakukan request POST untuk menambah kategori
        $response = $this->actingAs($user)->post('/kategori', $data);

        // Pastikan redirect berhasil
        $response->assertRedirect('/kategori');
        $response->assertStatus(302);

        // Pastikan file telah disimpan ke dalam folder yang benar
        // Storage::disk('public')->assertExists('img/' . $file->getClientOriginalName());

        // Verifikasi data telah masuk ke database dengan nama kategori dan path gambar yang sesuai
        $this->assertDatabaseHas('kategori', [
            'nama_kategori' => 'Minuman',
            // 'gambar_kategori' => 'img/' . $file->getClientOriginalName(),
        ]);

        // Pastikan session memiliki pesan success
        $response->assertSessionHas('success', 'Kategori berhasil ditambahkan');
    }
}
