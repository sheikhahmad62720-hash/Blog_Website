<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $authenticated = $this->user() !== null;

        return [
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
            'author_name' => [$authenticated ? 'nullable' : 'required', 'string', 'max:255'],
            'author_email' => [$authenticated ? 'nullable' : 'required', 'email', 'max:255'],
        ];
    }
}
