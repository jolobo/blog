<?php

namespace Atsys\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'post_categories' => 'required|array',
            'post_categories.*' => 'exists:post_categories,id',
            'published' => 'required|boolean',
            'image' => 'file|image',
            'title.*' => "required|max:255",
            'subtitle.*' => "required",
            'alias.*' => "required|unique:posts,alias|max:255",
            'meta_title.*' => "required",
            'meta_description.*' => "required",
            'short_description.*' => "required",
            'description.*' => "required",
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $alias_array = $this->alias;
            foreach (config('blog.languages') as $key => $language) {

                $alias = $alias_array[$key];
                array_shift($alias_array);

                if( in_array($alias, $alias_array) ) {
                    // TODO: locate the error
                    $validator->errors()->add('alias', 'The alias of a post must be different between languages!' ) ;
                    return;
                }
            }
        });
    }
}
