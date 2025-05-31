<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyCreateorUpdateSyncRequest extends FormRequest
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
            'address' => 'required',
            'city' => 'required',
            'comune_name' => 'required',
            'name' => 'required',
            'rut' => 'required',
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
            'uuid.required'  => __('company.get.uuid_required'),
            'address.required' => 'La dirección es requerida',
            'city.required' => 'La ciudad es requerida',
            'comune_name.required' => 'La comunas es requerida',
            'name.required' => 'La razón social es requerida',
            'rut.required' => 'El rut es requerido',
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, __('pagination.generic.error_message'))
        );
    }
}
