<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // أو تحقق من صلاحيات المستخدم إذا أردت
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|min:1',
            'description' => 'sometimes|string|min:1',
            'priority'  => 'sometimes|required|in:high,medium,low',
        ];
        // return [
        //     'title' => 'sometimes|required|string|min:1',
        //     // 'description' => 'sometimes|nullable|string|min:1', // تم التصحيح هنا // تم التصحيح هنا
        //     'description' => 'required|string|min:1', // تم التصحيح هنا // تم التصحيح هنا
        //     'priority' => 'sometimes|required|in:high,medium,low',
        // ];
        // return [
        //     'title' => 'sometimes|required|string|min:1',
        //     // 'description' => 'sometimes|nullable|string|min:1', // تم التصحيح هنا // تم التصحيح هنا
        //     'description' => 'sometimes|string|min:1', // تم التصحيح هنا // تم التصحيح هنا
        //     'priority' => 'sometimes|required|in:high,medium,low',
        // ];
    }
}
