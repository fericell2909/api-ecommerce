<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token'    => 'required',
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
            'token.required'    => "El token es requerido.",
            'password.required'    => "La contraseña es requerida.",
            'password.min'    => "La contraseña debe tener al menos 8 caracteres.",
            'password.max'    => "La contraseña debe tener máximo 16 caracteres.",
            'confirm_password.required' => "La confirmación de la contraseña es requerida.",
            'confirm_password.min'    => "La confirmación de la contraseña debe tener al menos 8 caracteres.",
            'confirm_password.max'    => "La confirmación de la contraseña debe tener máximo 16 caracteres.",
            'confirm_password.same' => "La confirmación de la contraseña no coincide con la contraseña."
        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, "Error al actualizar la nueva contraseña", 400)
        );
    }
}
