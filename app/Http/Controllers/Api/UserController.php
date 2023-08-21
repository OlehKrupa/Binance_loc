<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\CurrencyService;
use App\Services\CurrencyHistoryService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    /**
     * The CurrencyHistoryService instance.
     *
     * @var CurrencyHistoryService
     */
    private $currencyHistoryService;

    private $currencyService;

    public function __construct(UserService $userService, CurrencyHistoryService $currencyHistoryService, CurrencyService $currencyService)
    {
        // Apply 'auth' middleware to this controller, ensuring the user is authenticated
        $this->middleware('auth');
        $this->userService = $userService;
        $this->currencyHistoryService = $currencyHistoryService;
        $this->currencyService = $currencyService;
    }

    /**
     * API method to get user currency preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreferences(Request $request)
    {
        //return currency_ids
        try {
            // Retrieve the user's selected currencies using the UserService
            return response()->json($this->userService->getUserCurrenciesIds($request->user()));
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API method to get user currency preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreferencesData(Request $request)
    {
        //return user preferences array
        try {
            // Retrieve the user's selected currencies using the UserService
            return response()->json($this->userService->getUserCurrencies($request->user()));
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API method to set/update user currency preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPreferences(Request $request)
    {
        //return full created object (user with selected currencies id_s)
        try {
            $user = $request->user();
            $selectedCurrencies = $request->input('selectedCurrencies');

            // Check if the selectedCurrencies is an array
            if (!is_array($selectedCurrencies)) {
                // Return a JSON response with an error if the data format is invalid
                return response()->json(['error' => 'Invalid data format'], 400);
            }

            // Check if all selected currencies exist in the Currency::all() collection
            $allCurrencies = $this->currencyService->all()->pluck('id')->toArray();
            foreach ($selectedCurrencies as $currencyId) {
                if (!in_array($currencyId, $allCurrencies)) {
                    // Return a JSON response with an error if the currency is not found
                    return response()->json(['error' => 'Currency not found'], 404);
                }
            }

            // Clear previously selected cryptocurrencies for the user
            $this->userService->detachCurrencies($user);

            // Add the newly selected cryptocurrencies
            foreach ($selectedCurrencies as $currencyId) {
                $this->userService->attachCurrency($user, $currencyId);
            }

            // Return a success message as a JSON response after updating the preferences
            return response()->json(['message' => 'Preferences updated successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API method to toggle user subscription status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleSubscriptionStatus(Request $request)
    {
        try {
            $user = $request->user();
            $subscribedAt = $user->subscribed_at;

            // Toggle between null and current time
            if ($subscribedAt === null) {
                $user->subscribed_at = now();
            } else {
                $user->subscribed_at = null;
            }

            $user->save();

            // Return a success message as a JSON response after toggling the subscription status
            return response()->json(['message' => 'Subscription status toggled successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unTogglePremiumStatus(Request $request)
    {
        try {
            $user = $request->user();
            $premiumAt = $user->premium_at;

            // Toggle between null and current time
            if ($premiumAt != null) {
                $user->premium_at = null;
            } 

            $user->save();

            // Return a success message as a JSON response after toggling the subscription status
            return response()->json(['message' => 'Premium status untoggled successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API function to get user currency history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCurrencyHistory(Request $request)
    {
        try {
            $user = $request->user();

            // Retrieve the user's selected currencies using the UserService
            $selectedCurrencies = $this->userService->getUserCurrencies($user);

            // Get historical data for user's selected currencies using the CurrencyHistoryService
            $userCurrencies = $this->currencyHistoryService->getAllSelectedCurrencies($selectedCurrencies);

            // Prepare data to be sent as JSON response
            $responseData = [
                'userCurrencies' => $userCurrencies,
            ];

            // Return the data as a JSON response
            return response()->json($responseData);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}