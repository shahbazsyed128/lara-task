<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Employee;

class UpdateEmployeeRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $employee_table_name = resolve(Employee::class)->getTable();
        return [
            'first_name'      => ['required','string','min:3','max:35'],
            'last_name'       => ['required','string','min:3','max:35'],
            'company_id'      => ['required','numeric'], 
            'email'           => ['email',Rule::unique($employee_table_name)->ignore($this->request->get('id'))],
            'phone'           => ['numeric']
        ];
    }
}
