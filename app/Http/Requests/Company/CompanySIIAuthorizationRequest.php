<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanySIIAuthorizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'uuid' => 'required',
            'company_id' => 'required|exists:companies,id'
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

            'uuid.required' => __('company.updatecompanysii.uuid.required'),
            'company_id.exits' => __('company.updatecompanysii.company_id.required'),
            'company_id.required' => __('company.updatecompanysii.company_id.required')
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, __('company.updatecompanysii.user.pass.error_message'))
        );
    }
}
