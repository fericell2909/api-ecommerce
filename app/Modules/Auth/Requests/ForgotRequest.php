<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ForgotRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|exists:users,email|max:255',
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
            'email.required'    => 'El email es requerido',
            'email.max' => 'El email no debe ser mayor a 255 caracteres',
            'email.exists' => 'El email no existe.',
            'email.email' => 'El formato del email no es válido.'
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, "Error al reiniciar contraseña.",JsonResponse::HTTP_BAD_REQUEST)
        );
    }
}
