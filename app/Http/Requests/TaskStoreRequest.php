<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
            //
            // 'title' => 'required|string|min:1|unique:tasks,title',
            // 'descriotion' => 'required|string|min:1|unique:tasks,descriotion',
            'title' => 'required|string|min:1',
            'descriotion' => 'required|string|min:1',
            'priority'  => 'required|integer',
            'user_id'  => 'required|integer|exists:users,id',
        ];
    }
}
