<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Currency;
use App\Models\User;

class CurrencyControllerTest extends TestCase
{
    use RefreshDatabase; // Ensure the database is refreshed after each test

    /**
     * Test getting a list of all currencies.
     *
     * @return void
     */
    public function testGetAllCurrencies()
    {
        // Create some test currencies in the database
        Currency::factory()->count(5)->create();

        // Send a GET request to the index method of CurrencyController
        $response = $this->get('/api/currencies');

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response has the correct JSON structure for the list of currencies
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'full_name',
                'image_url',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    /**
     * Test creating a new currency.
     *
     * @return void
     */
    public function testCreateCurrency()
    {
        // Simulate a logged-in user (you may need to modify this depending on your authentication system)
        $this->actingAs(User::factory()->create());

        // Data for the new currency
        $data = [
            'name' => 'USD',
            'full_name' => 'United States Dollar',
            'image_url' => 'https://example.com/usd.png',
        ];

        // Send a POST request to the store method of CurrencyController with the data
        $response = $this->post('/api/currencies', $data);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response has the correct JSON structure for the created currency
        $response->assertJsonStructure([
            'id',
            'name',
            'full_name',
            'image_url',
            'created_at',
            'updated_at',
        ]);

        // Assert that the currency was actually created in the database
        $this->assertDatabaseHas('currency', $data);
    }

    /**
     * Test getting information about a specific currency.
     *
     * @return void
     */
    public function testGetCurrencyById()
    {
        // Create a test currency in the database
        $currency = Currency::factory()->create();

        // Send a GET request to the show method of CurrencyController with the currency ID
        $response = $this->get('/api/currencies/' . $currency->id);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response has the correct JSON structure for the currency
        $response->assertJsonStructure([
            'id',
            'name',
            'full_name',
            'image_url',
            'created_at',
            'updated_at',
        ]);

        // Assert that the response data matches the data of the currency in the database
        $response->assertJson([
            'id' => $currency->id,
            'name' => $currency->name,
            'full_name' => $currency->full_name,
            'image_url' => $currency->image_url,
            'created_at' => $currency->created_at->toISOString(),
            'updated_at' => $currency->updated_at->toISOString(),
        ]);
    }

    /**
     * Test updating information about a currency.
     *
     * @return void
     */
    public function testUpdateCurrency()
    {
        // Simulate a logged-in user (you may need to modify this depending on your authentication system)
        $this->actingAs(User::factory()->create());

        // Create a test currency in the database
        $currency = Currency::factory()->create();

        // Data for updating the currency
        $data = [
            'name' => 'EUR',
            'full_name' => 'Euro',
            'image_url' => 'https://example.com/eur.png',
        ];

        // Send a PUT request to the update method of CurrencyController with the currency ID and data
        $response = $this->put('/api/currencies/' . $currency->id, $data);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response has the correct JSON structure for the updated currency
        $response->assertJsonStructure([
            'id',
            'name',
            'full_name',
            'image_url',
            'created_at',
            'updated_at',
        ]);

        // Assert that the currency was actually updated in the database
        $this->assertDatabaseHas('currency', $data);
    }

    /**
     * Test deleting a currency.
     *
     * @return void
     */
    public function testDeleteCurrency()
    {
        // Simulate a logged-in user (you may need to modify this depending on your authentication system)
        $this->actingAs(User::factory()->create());

        // Create a test currency in the database
        $currency = Currency::factory()->create();

        // Send a DELETE request to the destroy method of CurrencyController with the currency ID
        $response = $this->delete('/api/currencies/' . $currency->id);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the currency was actually deleted from the database
        $this->assertDatabaseMissing('currency', ['id' => $currency->id]);
    }
}