<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|max:255',
            'password' => 'required|min:8|max:16',
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
            'email.min'         => 'El e-mail debe tener al menos 8 caracteres.',
            'email.max'         => 'El e-mail no debe ser mayor a 255 caracteres.',
            'email.email'       => 'El formato del e-mail no es válido.',
            'email.required'    => 'El e-mail es requerido.',
            'password.required' => 'La contraseña es requerida.',
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
