<?php

namespace Modules\SystemSetting\Http\Requests;

use App\Traits\ValidationMessage;
use Illuminate\Foundation\Http\FormRequest;
use function trans;
use Carbon\Carbon;

class StaffRequest extends FormRequest
{
    use ValidationMessage;

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
        if ($this->method() == "POST") {
            return [
                "name" => "required",
                "email" => "required|unique:users,email",
                "password" => "required|min:8",
                "department_id" => "required|numeric",
                "role_id" => "nullable|numeric",
                'photo' => 'nullable|mimes:jpeg,jpg,png',
                'signature_photo' => 'nullable|mimes:jpeg,jpg,png',
                'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone',
                'date_of_birth' => [
                            'required',
                            'date',
                            'before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
                        ],


            ];

        } else {
            return [
                
                "name" => "required",
                "email" => "required|unique:users,email," . $this->user_id,
                "password" => "required|min:8",
                "department_id" => "required|numeric",
                'date_of_birth' => [
                            'required',
                            'date',
                            'before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
                        ],
                "role_id" => "required|numeric",
                'photo' => 'nullable|mimes:jpeg,jpg,png',
                'signature_photo' => 'nullable|mimes:jpeg,jpg,png',
                'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone,' . $this->user_id,

            ];
        }

    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'username' => trans('retailer.Phone'),
        ];
    }
}
