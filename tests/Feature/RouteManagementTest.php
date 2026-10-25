<?php

namespace Tests\Feature;

use App\Models\Operator;
use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminTravel(): User
    {
        $operator = Operator::create([
            'name' => 'Test Operator Travel',
            'email' => 'test@operator.com',
            'phone' => '08123456789',
            'owner_name' => 'Budi Santoso',
            'address' => 'Jl. Test No. 1',
            'verification_status' => 'verified',
        ]);

        return User::factory()->create([
            'role' => 'admin_travel',
            'operator_id' => $operator->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_travel_can_view_routes_index(): void
    {
        $admin = $this->createAdminTravel();

        Route::create([
            'operator_id' => $admin->operator_id,
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'price' => 150000,
            'estimated_duration' => '3 jam',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin-travel.routes.index'));

        $response->assertOk();
        $response->assertSee('Jakarta');
        $response->assertSee('Bandung');
    }

    public function test_admin_travel_can_view_route_create_page_without_city_dropdown(): void
    {
        $admin = $this->createAdminTravel();

        $response = $this->actingAs($admin)->get(route('admin-travel.routes.create'));

        $response->assertOk();
        $response->assertSee('name="origin"', false);
        $response->assertSee('name="destination"', false);
        $response->assertDontSee('name="origin_city_id"', false);
        $response->assertDontSee('name="destination_city_id"', false);
    }

    public function test_admin_travel_can_create_route_with_manual_origin_and_destination(): void
    {
        $admin = $this->createAdminTravel();

        $response = $this->actingAs($admin)->post(route('admin-travel.routes.store'), [
            'origin' => 'Lhokseumawe',
            'destination' => 'Banda Aceh',
            'price' => 120000,
            'estimated_duration' => '5 jam',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin-travel.routes.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('routes', [
            'operator_id' => $admin->operator_id,
            'origin' => 'Lhokseumawe',
            'destination' => 'Banda Aceh',
            'price' => 120000,
        ]);
    }

    public function test_route_creation_validation_requires_different_origin_and_destination(): void
    {
        $admin = $this->createAdminTravel();

        $response = $this->actingAs($admin)->post(route('admin-travel.routes.store'), [
            'origin' => 'Medan',
            'destination' => 'Medan',
            'price' => 100000,
        ]);

        $response->assertSessionHasErrors('destination');
    }

    public function test_admin_travel_can_edit_and_update_route(): void
    {
        $admin = $this->createAdminTravel();

        $route = Route::create([
            'operator_id' => $admin->operator_id,
            'origin' => 'Medan',
            'destination' => 'Banda Aceh',
            'price' => 200000,
            'estimated_duration' => '10 jam',
            'is_active' => true,
        ]);

        $editPage = $this->actingAs($admin)->get(route('admin-travel.routes.edit', $route));
        $editPage->assertOk();
        $editPage->assertSee('value="Medan"', false);
        $editPage->assertSee('value="Banda Aceh"', false);

        $updateResponse = $this->actingAs($admin)->put(route('admin-travel.routes.update', $route), [
            'origin' => 'Medan',
            'destination' => 'Takengon',
            'price' => 220000,
            'estimated_duration' => '9 jam',
            'is_active' => 1,
        ]);

        $updateResponse->assertRedirect(route('admin-travel.routes.index'));
        $this->assertDatabaseHas('routes', [
            'id' => $route->id,
            'origin' => 'Medan',
            'destination' => 'Takengon',
            'price' => 220000,
        ]);
    }
}
