<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Models\Setting;
use App\Models\Banner;
use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles and Permissions Setup
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage_users',
            'manage_settings',
            'manage_banners',
            'backup_restore_db',
            'view_logs',
            'manage_categories',
            'manage_informations',
            'manage_versions',
            'view_informations',
            'manage_bookmarks',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin Kesekretariatan']);
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer']);

        // Give all permissions to Super Admin
        $superAdminRole->syncPermissions(Permission::all());

        // Admin permissions
        $adminRole->syncPermissions([
            'manage_categories',
            'manage_informations',
            'manage_versions',
            'view_informations',
            'manage_bookmarks',
        ]);

        // Viewer permissions
        $viewerRole->syncPermissions([
            'view_informations',
            'manage_bookmarks',
        ]);

        // 2. Default Users
        $superAdmin = User::firstOrCreate([
            'email' => 'superadmin@imst.com',
        ], [
            'name' => 'Super Admin IMST',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->syncRoles([$superAdminRole]);

        $admin = User::firstOrCreate([
            'email' => 'admin@imst.com',
        ], [
            'name' => 'Admin Sekretariat IMST',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $admin->syncRoles([$adminRole]);

        $viewer = User::firstOrCreate([
            'email' => 'viewer@imst.com',
        ], [
            'name' => 'Viewer User',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $viewer->syncRoles([$viewerRole]);

        // 3. Settings & Company Profile Setup
        Setting::setValue('site_name', 'IMST Corporate Information Portal');
        Setting::setValue('site_description', 'Pusat Informasi Terintegrasi dan Terpercaya PT IMST');
        Setting::setValue('maintenance_mode', 'false');
        
        // Add settings with labels and types
        Setting::where('key', 'site_name')->update(['label' => 'Site Name', 'group' => 'general', 'type' => 'text']);
        Setting::where('key', 'site_description')->update(['label' => 'Site Description', 'group' => 'general', 'type' => 'textarea']);
        Setting::where('key', 'maintenance_mode')->update(['label' => 'Maintenance Mode', 'group' => 'system', 'type' => 'boolean']);

        CompanyProfile::setValue('name', 'PT Industri Manufaktur Sinergi Teknologi (IMST)');
        CompanyProfile::where('key', 'name')->update(['label' => 'Nama Perusahaan']);
        CompanyProfile::setValue('address', 'Jl. Jenderal Sudirman No. 12, Jakarta, Indonesia');
        CompanyProfile::where('key', 'address')->update(['label' => 'Alamat']);
        CompanyProfile::setValue('phone', '+62 21 1234 5678');
        CompanyProfile::where('key', 'phone')->update(['label' => 'No. Telepon']);

        // 4. Default Categories
        $categories = [
            ['name' => 'Kebijakan Perusahaan', 'slug' => 'kebijakan-perusahaan', 'description' => 'Dokumen dan panduan regulasi internal perusahaan.'],
            ['name' => 'Laporan Bulanan', 'slug' => 'laporan-bulanan', 'description' => 'Informasi kinerja divisi bulanan.'],
            ['name' => 'Pengumuman Resmi', 'slug' => 'pengumuman-resmi', 'description' => 'Edaran dan informasi penting manajemen.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 5. Default Banners
        Banner::firstOrCreate([
            'title' => 'Selamat Datang di Portal IMST',
        ], [
            'description' => 'Akses informasi korporat, kebijakan terbaru, dan histori laporan bulanan dalam satu platform terintegrasi.',
            'image_path' => 'banners/default_banner_1.jpg',
            'link_url' => '#',
            'order' => 1,
            'is_active' => true,
        ]);

        Banner::firstOrCreate([
            'title' => 'Single Source of Truth',
        ], [
            'description' => 'Portal terpusat untuk menjaga konsistensi dokumen dan aksesibilitas bagi seluruh karyawan.',
            'image_path' => 'banners/default_banner_2.jpg',
            'link_url' => '#',
            'order' => 2,
            'is_active' => true,
        ]);

        // 6. Default Information & Monthly Versions
        $catKebijakan = Category::where('slug', 'kebijakan-perusahaan')->first();
        
        $info = Information::firstOrCreate([
            'slug' => 'kebijakan-keamanan-informasi',
        ], [
            'title' => 'Kebijakan Keamanan Informasi IMST',
            'category_id' => $catKebijakan->id,
            'thumbnail' => 'thumbnails/default_security.jpg',
            'summary' => 'Ringkasan standar dan kebijakan keamanan siber di lingkungan kerja PT IMST.',
            'status' => 'published',
            'author_id' => $admin->id,
            'published_at' => now(),
        ]);

        // Create Monthly Versions for 2026
        // January Version
        InformationVersion::firstOrCreate([
            'information_id' => $info->id,
            'month' => 1,
            'year' => 2026,
        ], [
            'rich_content' => '<h2><strong>Kebijakan Keamanan Informasi - Versi Januari 2026</strong></h2><p>Pada bulan Januari ini, fokus kebijakan keamanan informasi adalah pengenalan protokol otentikasi multi-faktor (MFA) di seluruh akun internal perusahaan. Seluruh staf diwajibkan mengaktifkan MFA paling lambat tanggal 31 Januari 2026.</p><p>Panduan pengaktifan dapat diunduh pada lampiran yang tersedia.</p>',
            'created_by' => $admin->id,
        ]);

        // February Version
        InformationVersion::firstOrCreate([
            'information_id' => $info->id,
            'month' => 2,
            'year' => 2026,
        ], [
            'rich_content' => '<h2><strong>Kebijakan Keamanan Informasi - Versi Februari 2026</strong></h2><p>Pada bulan Februari 2026, ditambahkan kebijakan kebersihan meja kerja digital (Clean Desk Policy) untuk menjamin perlindungan data fisik dan layar komputer dari pihak ketiga yang tidak berwenang saat meninggalkan ruangan.</p><p>Setiap karyawan wajib mengunci layar komputer (Win + L) saat meninggalkan meja kerja.</p>',
            'created_by' => $admin->id,
        ]);
    }
}
