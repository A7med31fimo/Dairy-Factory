<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default operator user
        User::create([
            'name'     => 'مشغل النظام',
            'email'    => 'admin@dairy.com',
            'password' => Hash::make('password123'),
            'role'     => 'operator',
        ]);

        $this->command->info('✅ تم إنشاء المستخدم الافتراضي:');
        $this->command->info('   البريد: admin@dairy.com');
        $this->command->info('   كلمة المرور: password123');
    }
}
