<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Company;

class StoreCompanyRequest extends FormRequest
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
        $company_table_name = resolve(Company::class)->getTable();
        return [
            'name'      => ['required','string','min:3','max:35'],
            'email'     => ['required','string',Rule::unique($company_table_name)],
            'website'   => ['url']
        ];
    }
}
