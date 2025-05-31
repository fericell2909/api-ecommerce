<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

class UserGeneralInformationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
        ];
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

    /**
     * @return array
     * Custom validation message
     */
    public function messages()
    {
        return [
            'name.required'    => __('auth.name.required'),
            'email.required'    => __('auth.email.required'),
            'email.unique'      => __('auth.email.unique'),
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, __('settings.generic.update.error_message'))
        );
    }
}
