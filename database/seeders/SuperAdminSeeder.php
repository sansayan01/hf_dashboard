<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\BankDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'employee_id' => 'HFSA000001',
            'email' => 'admin@humanityfoundation.org',
            'password' => Hash::make('Admin@123'),
            'designation' => 'super_admin',
            'parent_id' => null,
            'status' => 'active',
        ]);

        // Create Super Admin Profile
        UserProfile::create([
            'user_id' => $superAdmin->id,
            'full_name' => 'Super Administrator',
            'phone_number' => '9999999999',
            'blood_group' => 'O+',
            'address' => 'Humanity Foundation HQ',
            'state' => 'West Bengal',
            'district' => 'Kolkata',
            'pin_code' => '700001',
        ]);

        // Create Super Admin Bank Details
        BankDetail::create([
            'user_id' => $superAdmin->id,
            'bank_name' => 'State Bank of India',
            'account_number' => '00000000000',
            'ifsc_code' => 'SBIN0000000',
        ]);

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: admin@humanityfoundation.org');
        $this->command->info('Password: Admin@123');
    }
}
