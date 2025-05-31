<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdatemeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|min:1|max:255',
            'surnames' => 'required|min:1|max:255',
            'request_new_password' => 'required|boolean',
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
     * Custom validation message
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.min'          => 'El nombre debe tener al menos 1 caracter.',
            'name.max'          => 'El nombre no debe ser mayor a 255 caracteres.',
            'name.required'     => 'El nombre es requerido.',
            'surnames.min'      => 'Los apellidos deben tener al menos 1 caracter.',
            'surnames.max'      => 'Los apellidos no deben ser mayor a 255 caracteres.',
            'surnames.required' => 'Los apellidos son requeridos.',
            'request_new_password.required' => 'El campo request_new_password es requerido.',
            'request_new_password.boolean'  => 'El campo request_new_password debe ser un valor booleano.',

        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors,'Solicitu Inválida')
        );
    }
}
