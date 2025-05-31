<?php

namespace App\Modules\File\Requests;

use App\Modules\Auth\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class FileStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        return [
                    //'file' => 'required|file|mimes:xls,xlsx,jpeg|max:2048'];
                    'file' => 'required|file|max:2048'];
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
            'file.required' => 'Por favor, seleccione un archivo.',
            'file.file' => 'El campo debe ser un archivo.',
            //'file.mimes' => 'El archivo debe ser de uno de los siguientes tipos: XLS o XLSX.',
            'file.max' => 'El tamaño máximo del archivo permitido es de 2 MB.'
        ];

    }

    public  function failedValidation(Validator $validator)
    {

        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, "Ha ocurrido un error de validación.", 400)
        );

    }
}
