<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class DocumentCreateorUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'action' => 'required',
            'action_document_status' => 'required',
            'company_id' => 'required|exists:companies,id',
            'currency_id' => 'required|exists:currencies,id',
            // 'method_payment_dtes_id' => 'required|exists:method_payment_dtes,id',
            'status_id' => 'required|exists:document_status,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'type_dte_id' => 'required|exists:type_dtes,id',
            // 'type_payment_dtes_id' => 'required|exists:type_payment_dtes,id',
            'user_id' => 'required|exists:users,id',
            'uuid' => 'required'
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

            'validation.action.required' =>  __('document.validation.action.required'),
            'validation.action_document_status.required' =>  __('document.validation.action_document_status.required'),
            'validation.company_id.required' =>  __('document.validation.company_id.required'),
            'validation.company_id.exists' =>  __('document.validation.company_id.exists'),
            'validation.currency_id.required' => __('document.validation.currency_id.required'),
            'validation.currency_id.exists' =>  __('document.validation.currency_id.exists'),
            'validation.method_payment_dtes_id.required' =>  __('document.validation.method_payment_dtes_id.required'),
            'validation.method_payment_dtes_id.exists' =>  __('document.validation.method_payment_dtes_id.exists'),

            'validation.status_id.required' =>  __('document.validation.status_id.required'),
            'validation.status_id.exists' =>  __('document.validation.status_id.exists'),

            'validation.supplier_id.required' =>  __('document.validation.supplier_id.required'),
            'validation.supplier_id.exists' =>  __('document.validation.supplier_id.exists'),

            'validation.type_dte_id.required' =>  __('document.validation.type_dte_id.required'),
            'validation.type_dte_id.exists' =>  __('document.validation.type_dte_id.exists'),

            'validation.type_payment_dtes_id.required' =>  __('document.validation.type_payment_dtes_id.required'),
            'validation.type_payment_dtes_id.exists' =>  __('document.validation.type_payment_dtes_id.exists'),

            'validation.user_id.required' =>  __('document.validation.user_id.required'),
            'validation.user_id.exists' =>  __('document.validation.user_id.exists'),

            'validation.uuid' =>  __('document.validation.uuid'),


        ];
    }

    public  function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            $this->responseError($errors, __('document.createorupdate.error_message'))
        );
    }
}
