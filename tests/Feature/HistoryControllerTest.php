<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Currency;
use App\Models\CurrencyHistory;
use App\Models\User;

class HistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting a list of all currency exchange rate history records.
     *
     * @return void
     */
    public function testGetAllCurrencyHistoryRecords()
    {
        // Create some test currency exchange rate history records in the database
        CurrencyHistory::factory()->count(5)->create();

        // Get an authenticated user with Sanctum token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Send a GET request to the index method of HistoryController with the Bearer token
        $response = $this->getJson('/api/history');

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response has the correct JSON structure for the list of currency exchange rate history records
        $response->assertJsonStructure([
            '*' => [
                'id',
                'currency_id',
                'sell',
                'buy',
                'updated_at',
            ]
        ]);
    }

    /**
     * Test analyzing the currency trend for selected currencies.
     *
     * @return void
     */
    public function testAnalyzeCurrencyTrend()
    {
        // Get an authenticated user with Sanctum token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Send a GET request to the analyzeCurrencyTrend method of HistoryController with the Bearer token
        $response = $this->getJson('/api/history/analyze-trend');

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response data is as expected (you may need to define the expected data for this test)
        // $response->assertJson([...]);
    }

    /**
     * Test getting the last recorded currencies for selected currencies.
     *
     * @return void
     */
    public function testGetLastCurrencies()
    {
        // Get an authenticated user with Sanctum token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Send a GET request to the getLastCurrencies method of HistoryController with the Bearer token
        $response = $this->getJson('/api/history/last-currencies');

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response data is as expected (you may need to define the expected data for this test)
        // $response->assertJson([...]);
    }

    /**
     * Test getting all currency exchange rate history records for a specific currency.
     *
     * @return void
     */
    public function testGetCurrencyHistoryRecordsByCurrencyId()
    {
        // Create a test currency exchange rate history record in the database
        $currencyHistoryRecord = CurrencyHistory::factory()->create();

        // Get an authenticated user with Sanctum token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Send a GET request to the show method of HistoryController with the currency ID and Bearer token
        $response = $this->getJson('/api/history/' . $currencyHistoryRecord->currency_id);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response has the correct JSON structure for the currency exchange rate history record
        $response->assertJsonStructure([
            '*' => [
                'id',
                'currency_id',
                'sell',
                'buy',
                'updated_at',
            ]
        ]);
    }

    /**
     * Test deleting a currency exchange rate history record.
     *
     * @return void
     */
    public function testDeleteCurrencyHistoryRecord()
    {
        // Get an authenticated user with Sanctum token
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a test currency exchange rate history record in the database
        $currencyHistoryRecord = CurrencyHistory::factory()->create();

        // Send a DELETE request to the destroy method of HistoryController with the currency exchange rate history record ID and Bearer token
        $response = $this->deleteJson('/api/history/' . $currencyHistoryRecord->id);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the currency exchange rate history record was actually deleted from the database
        $this->assertDatabaseMissing('currency_history', ['id' => $currencyHistoryRecord->id]);
    }
}