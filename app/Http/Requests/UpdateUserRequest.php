<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
          
            'email' => 'required|string|email|unique:users,email, '.$this->id.'|max:191',
            'name'=>'required',
            'user_catalogue_id'=> 'required|gt:0',
          


        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Email chưa đúng định dạng.Ví dụ: abc@gmail.com',
            'email.unique' => 'Email đã tồn tại.Hãy chọn email khác',
            'email.string' => 'Email phải là dạng ký tự',
            'email.max' => 'Độ dài email tối đa 191 ký tự',
            'name.required' => 'Bạn chưa nhập họ tên',
            'name.string' => 'Họ tên phải là dạng ký tự',
            'user_catalogue_id.gt' => 'Bạn chưa chọn nhóm thành viên',
            'user_catalogue_id.required' => 'Vui lòng chọn nhóm thành viên',
           
        ];
    }
}
