<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CurrencyHistory;
use App\Services\CurrencyHistoryService;
use Illuminate\Support\Facades\Auth;

class HomeFilterRequest extends FormRequest
{

    /**
     * The CurrencyHistoryService instance.
     *
     * @var CurrencyHistoryService
     */
    private $currencyHistoryService;

    /**
     * Create a new command instance.
     *
     * @param CurrencyHistoryService $currencyHistoryService
     */
    public function __construct(
        CurrencyHistoryService $currencyHistoryService
    ) {
        parent::__construct();
        $this->currencyHistoryService = $currencyHistoryService;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $dayCurrencies = $this->currencyHistoryService->getUniqueCurrenciesId();

        $rules = [];

        if ($this->has('newDateRange')) {
            $rules['newDateRange'] = 'required|integer|min:0';
        }

        if ($this->has('newCurrencyId')) {
            $rules['newCurrencyId'] = 'required|in:' . implode(',', $dayCurrencies);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'newDateRange.required' => 'The date range field is required.',
            'newDateRange.integer' => 'The date range must be an integer.',
            'newDateRange.min' => 'The date range must be at least 1.',
            'newCurrencyId.required' => 'The currency ID field is required.',
            'newCurrencyId.in' => 'The selected currency ID is invalid.',
        ];
    }
}