<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }
        $env = file_get_contents($envPath);
        $newLine = 'ADMIN_PASSWORD=admin123'; // ubah sesuai kebutuhan

        if (preg_match('/^ADMIN_PASSWORD=.*$/m', $env)) {
            $env = preg_replace('/^ADMIN_PASSWORD=.*$/m', $newLine, $env);
        } else {
            $env .= PHP_EOL . $newLine . PHP_EOL;
        }

        file_put_contents($envPath, $env);
    }
}