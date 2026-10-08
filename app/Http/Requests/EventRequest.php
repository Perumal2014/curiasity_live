<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'event_title' => "required",

            'from_date' => "required",
            'to_date' => "required",
            'start_time' => "required",
            'end_time' => "required",
            'event_url' => "sometimes|url",
            'event_des' => "required",
            'event_location' => 'required',
            'host_type' => 'required',
        ];
        if ($this->host_type == 1) {
            $rules['instructor'] = "required";
        } else {
            $rules['host_name'] = "required";
        }


        if (!$this->id) {
            $rules['for_whom'] = "required";
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
