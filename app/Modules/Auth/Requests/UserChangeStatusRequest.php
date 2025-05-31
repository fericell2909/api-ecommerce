<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UserChangeStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        return ['id' => 'required|integer|exists:App\Modules\Auth\Models\User,id'];

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

        return ['id.required' => 'El ID del Usuario es requerido.','id.integer' => 'EL ID del Usuario debe ser un número.','id.exists' => 'EL ID del Usuario no existe.'];

    }

    public  function failedValidation(Validator $validator)
    {

        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, "Ha ocurrido un error de validación.", 400)
        );

    }
}
