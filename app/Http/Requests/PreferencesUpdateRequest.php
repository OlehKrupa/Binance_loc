<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PreferencesUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $maxCryptocurrencies = config('services.max_cryptocurrencies');

        return [
            'selectedCurrencies' => [
                'required',
                'array',
                'min:1',
                'max:' . $maxCryptocurrencies,
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $maxCryptocurrencies = config('services.max_cryptocurrencies');

        return [
            'selectedCurrencies.required' => 'Please select at least one cryptocurrency.',
            'selectedCurrencies.array' => 'The selected currencies must be an array.',
            'selectedCurrencies.min' => 'Please select at least one cryptocurrency.',
            'selectedCurrencies.max' => 'You can select a maximum of ' . $maxCryptocurrencies . ' cryptocurrencies.',
        ];
    }
}