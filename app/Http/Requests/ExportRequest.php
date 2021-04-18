<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    /**
     * Allowed file formats.
     *
     * @var array
     */
    public $allowed_formats = [
        'xlsx',
        'csv'
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->showView()) {
            return [];
        }

        return [
            'take' => 'required|bail|integer|gte:10',
            'format' => 'required|string|in:' . implode(',', $this->allowed_formats),
        ];
    }

    /**
     * Whether show view or not.
     *
     * @return  bool
     */
    public function showView()
    {
        if ($this->has('export') && $this->input('export') == 1) {
            return false;
        }

        return true;
    }
}
