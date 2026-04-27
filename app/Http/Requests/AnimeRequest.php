<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|min:2|max:255',
            'genre'       => 'required|string|max:255',
            'type'        => 'nullable|string|max:50',
            'episodes'    => 'nullable|integer|min:0',
            'year'        => 'nullable|integer|min:1950|max:' . (date('Y') + 1),
            'studio'      => 'nullable|string|max:255',
            'rating'      => 'nullable|numeric|min:0|max:10',
            'description' => 'nullable|string|max:5000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:ongoing,completed,announced',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'  => 'Название обязательно',
            'title.min'       => 'Название минимум 2 символа',
            'genre.required'  => 'Жанр обязателен',
            'status.required' => 'Статус обязателен',
            'status.in'       => 'Недопустимый статус',
            'rating.max'      => 'Рейтинг не может быть больше 10',
            'year.max'        => 'Год не может быть больше ' . (date('Y') + 1),
            'image.image' => 'Файл должен быть картинкой',
            'image.mimes' => 'Допустимые форматы: jpeg, png, jpg, webp',
            'image.max'  => 'Максимальный размер: 2 МБ',
        ];
    }
}