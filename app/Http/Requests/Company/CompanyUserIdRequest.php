<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;

class CompanyUserIdRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return User::where('id',request()->route('id'))->exists();
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'id.required'  => 'El usuario es requerido',
            'id.exists' => 'El usuario no ha sido encontrado',
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, 'Error en los parametros de búsqueda')
        );
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            $this->responseError([['id' => 'El usuario no existe']], 'Error en los parametros de búsqueda')
        );
    }

}
