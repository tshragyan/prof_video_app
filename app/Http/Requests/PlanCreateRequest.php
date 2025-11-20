<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'max_video_count' => 'required|numeric',
            'max_video_size' => 'required|numeric',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'annual_price' => 'nullable|numeric',
            'old_annual_price' => 'nullable|numeric',
        ];
    }
}
