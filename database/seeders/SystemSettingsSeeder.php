<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::firstOrCreate(
            ['id' => 1], // ensures only one default record
            [
                'logo'            => 'default-logo.png',
                'mini_logo'       => 'default-mini-logo.png',
                'favicon'         => 'default-favicon.ico',
                'system_title'    => 'My System',
                'company_name'    => 'My Company',
                'tag_line'        => 'Your tagline here',
                'phone_number'    => '+880123456789',
                'whatsapp_number' => '+880123456789',
                'email'           => 'info@mycompany.com',
                'copyright_text'  => '© ' . date('Y') . ' My Company. All rights reserved.',
            ]
        );
    }
}
