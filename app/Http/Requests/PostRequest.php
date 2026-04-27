<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Пока без авторизации
    }

    public function rules(): array
    {
        return [
            'title' => 'required|min:3|max:255',
            'body'  => 'required|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок обязателен',
            'title.min'      => 'Заголовок минимум 3 символа',
            'body.required'  => 'Текст обязателен',
            'body.min'       => 'Текст минимум 10 символов',
        ];
    }
}