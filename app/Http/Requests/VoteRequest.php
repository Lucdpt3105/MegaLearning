<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'forum_question_id' => 'nullable|exists:forumquestions,forum_question_id',
            'forum_answer_id'   => 'nullable|exists:forumanswers,forum_answer_id',
            'value' => 'required|in:1,-1'            
        ];
    }
}
