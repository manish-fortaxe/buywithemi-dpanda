<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $id
 * @property string $url
 * @property string $image
 * @property int $status
 */
class BlogAddRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required',
            'category_id' => 'required',
            'media' => 'required',
            'description' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => translate('the_title_field_is_required'),
            'media.required' => translate('the_image_is_required'),
            'category_id.required' => translate('the_category_is_required'),
            'description.required' => translate('the_description_is_required')
        ];
    }

}
