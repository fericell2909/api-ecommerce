<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required|min:8|max:16',
            'confirm_password' => 'required|Same:password|min:8|max:16'
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
            'password.required'    => __('auth.password.required'),
            'password.min'    => __('auth.password.min'),
            'password.max'    => __('auth.password.max'),
            'confirm_password.required' => __('auth.confirm_password.required'),
            'confirm_password.min'    => __('auth.confirm_password.min'),
            'confirm_password.max'    => __('auth.confirm_password.max'),
            'confirm_password.Same' => __('auth.confirm_password.different')
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, __('auth.change.error_message'))
        );
    }
}
