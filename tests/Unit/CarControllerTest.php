<?php

namespace Tests\Unit;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CarControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function test_index_method_returns_view_with_cars()
    {
        $this->withoutMiddleware();

        $response = $this->get(route('admin.car.index'));
        $response->assertStatus(200);
        $response->assertViewIs('cars.dashboard');
    }

    /** @test */
    public function test_create_method_returns_view()
    {
        $this->withoutMiddleware();

        $response = $this->get(route('admin.car.create'));

        $response->assertViewIs('cars.create');
    }

    /** @test */
    public function test_store_method_returns_error_when_required_fields_are_missing()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $carData = [
            'make' => '',
            'model' => '',
            'year' => '',
        ];

        $response = $this->actingAs($user)->post(route('admin.car.store'), $carData);

        $response->assertSessionHasErrors(['make', 'model', 'year']);
        $this->assertDatabaseMissing('cars', $carData);
        $response->assertRedirect();
    }
    
    /** @test */
    public function test_edit_method_returns_view_with_car()
    {
        $this->withoutMiddleware();
        
        $car = Car::factory()->create();

        $response = $this->get(route('admin.car.edit', $car->id));

        $response->assertViewIs('cars.edit');
        $response->assertViewHas('car', $car);
    }

    /** @test */
    public function test_update_method_updates_car()
    {
        $this->withoutMiddleware();

        $car = Car::factory()->create();
        $user = User::factory()->create();
        $carData = [
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => '2010',
        ];

        $response = $this->actingAs($user)->post(route('admin.car.update', $car->id), $carData);

        $this->assertDatabaseHas('cars', $carData);
        $response->assertRedirect();
    }

    /** @test */
    public function test_destroy_method_deletes_car()
    {
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();
        $car = Car::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.car.destroy', $car->id));

        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
        $response->assertRedirect();
    }
}
