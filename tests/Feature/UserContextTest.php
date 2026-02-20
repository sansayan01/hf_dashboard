<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserContextTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that getEffectiveUser returns the authenticated user when no session is set.
     */
    public function test_get_effective_user_returns_current_user_by_default()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $effectiveUser = User::getEffectiveUser();

        $this->assertEquals($user->id, $effectiveUser->id);
    }

    /**
     * Test that getEffectiveUser returns the target user when session is set and authorized.
     */
    public function test_get_effective_user_returns_view_as_user_when_authorized()
    {
        $admin = User::factory()->create(['designation' => 'super_admin']);
        $target = User::factory()->create();

        $this->actingAs($admin);
        session(['view_as_user_id' => $target->id]);

        $effectiveUser = User::getEffectiveUser();

        $this->assertEquals($target->id, $effectiveUser->id);
    }

    /**
     * Test that getEffectiveUser resets and returns current user when target is unauthorized.
     */
    public function test_get_effective_user_clears_session_when_unauthorized()
    {
        // Two users with no hierarchy relation
        $user1 = User::factory()->create(['designation' => 'rm']);
        $user2 = User::factory()->create(['designation' => 'rm']);

        $this->actingAs($user1);
        session(['view_as_user_id' => $user2->id]);

        $effectiveUser = User::getEffectiveUser();

        $this->assertEquals($user1->id, $effectiveUser->id);
        $this->assertFalse(session()->has('view_as_user_id'));
    }
}
