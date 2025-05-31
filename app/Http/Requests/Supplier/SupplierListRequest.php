<?php

namespace App\Http\Requests\Supplier;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplierListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => 'required',
            'status' => 'required',
            'name' => 'sometimes',
            'rut' => 'sometimes',
            'perPage' => 'required|numeric',
            'page'       => 'required|numeric',
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
            'q.required'  =>  __('pagination.generic.q.required'), # 'El criterio de busqueda es requerido',
            'perPage.required' => __('pagination.generic.perPage.required'), #'El numero de registros es requerido',
            'page.required' => __('pagination.generic.perPage.required'), #'El numero de pagina es requerido',
            // 'rol.required'  => 'El rol es requerido',
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
