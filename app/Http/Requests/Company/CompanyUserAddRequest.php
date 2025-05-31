<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyUserAddRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        return [
            'name'    => 'required',
            'email'    => 'required|max:255|email|unique:users',
            'password' => 'required|min:8|max:16',
            'company_id' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'name.required'    => __('auth.name.required'),
            'email.required'    => __('auth.email.required'),
            'email.unique'      => __('auth.email.unique'),
            'password.required' => __('auth.password.required'),
            'password.min' => __('auth.password.min'),
            'password.max' => __('auth.password.max'),
            'company_id.required' => __('company.updatestatus.company_id.required')
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, __('company.updatestatus.error_message'))
        );
    }
}
