<?php

namespace App\Http\Requests\Supplier;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplierBulkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'items.*.Nombre' => 'required|string|max:255',
            'items.*.RUT' => 'required',
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
            'items.*.Nombre.required' => __('supplier.validation.name.required'),
            'items.*.Nombre.max' => __('supplier.validation.name.max'),
            'items.*.RUT.required' => __('supplier.validation.rut.required'),
            'items.*.RUT.max' => __('supplier.validation.rut.max'),
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, __('supplier.error.generic'))
        );
    }
}
