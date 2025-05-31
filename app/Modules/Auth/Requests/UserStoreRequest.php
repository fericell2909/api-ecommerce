<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
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
            'email'    => 'required|email|max:255|unique:users,email',
            #'password' => 'required|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/',
            'roles'    => 'required|array',
            'roles.*.role_id' => 'required|integer|exists:App\Modules\PermissionsRoles\Models\Role,id|distinct'
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
            'email.min'         => 'El e-mail debe tener al menos 8 caracteres.',
            'email.max'         => 'El e-mail no debe ser mayor a 255 caracteres.',
            'email.email'       => 'El formato del e-mail no es válido.',
            'email.required'    => 'El e-mail es requerido.',
            'email.unique'      => 'El e-mail ya está en uso.',
            'roles.required'    => 'El rol es requerido.',
            'roles.array'       => 'El rol debe ser un arreglo.',
            'roles.*.role_id.required' => 'El ID del rol es requerido.',
            'roles.*.role_id.integer'  => 'El ID del rol debe ser un número entero.',
            'roles.*.role_id.exists'   => 'El ID del rol no existe.',
            'roles.*.role_id.distinct' => 'El ID del rol no debe estar repetido.'
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
