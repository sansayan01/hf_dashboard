<?php

namespace Tests\Feature;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_premium_member_can_download_pvc_card()
    {
        // 1. Create a Super Admin for full access
        $admin = User::factory()->create(['designation' => 'super_admin']);
        $this->actingAs($admin);

        // 2. Create a premium member
        $member = Survey::create([
            'full_name' => 'Test Member',
            'patient_id' => 'HFPM000001',
            'phone_number' => '9876543210',
            'address' => 'Test Address',
            'age' => 25,
            'gender' => 'female',
            'pin' => '700001',
            'is_member' => true,
            'created_by' => $admin->id
        ]);

        // 3. Request download
        $response = $this->get(route('membership.card.download', $member->id));

        // 4. Assert success and PDF content type
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="PVC_Card_HFPM000001.pdf"');
    }

    public function test_non_member_cannot_download_pvc_card()
    {
        $admin = User::factory()->create(['designation' => 'super_admin']);
        $this->actingAs($admin);

        // Create a non-member
        $nonMember = Survey::create([
            'full_name' => 'Regular Patient',
            'patient_id' => 'HFP0000001',
            'phone_number' => '9876543210',
            'address' => 'Test Address',
            'age' => 25,
            'gender' => 'female',
            'pin' => '700001',
            'is_member' => false,
            'created_by' => $admin->id
        ]);

        $response = $this->get(route('membership.card.download', $nonMember->id));

        // Should redirect back with error or 403/404 depending on implementation
        // My implementation returns back() with error
        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    public function test_unauthorized_user_cannot_download_pvc_card()
    {
        // Create an RO
        $ro = User::factory()->create(['designation' => 'ro']);

        // Create another RO
        $otherRo = User::factory()->create(['designation' => 'ro']);

        // Create a member for the first RO
        $member = Survey::create([
            'full_name' => 'Test Member',
            'patient_id' => 'HFPM000001',
            'phone_number' => '9876543210',
            'address' => 'Test Address',
            'age' => 25,
            'gender' => 'female',
            'pin' => '700001',
            'is_member' => true,
            'created_by' => $ro->id
        ]);

        // Act as the other RO
        $this->actingAs($otherRo);

        $response = $this->get(route('membership.card.download', $member->id));

        // Should be forbidden
        $response->assertStatus(403);
    }
}
