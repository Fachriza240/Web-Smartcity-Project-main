<?php

namespace App\Rules;

use Illuminate\Validation\Rule;

final class DssClassification
{
    public static function prepare(\Illuminate\Http\Request $request): void
    {
        if ($request->boolean('dss_classification_form')) {
            $request->mergeIfMissing(['sdgs' => [], 'smart_city_pillars' => []]);
        }
    }

    public static function rules(): array
    {
        return [
            'sdgs' => ['nullable', 'array', 'list', 'max:2'],
            'sdgs.*' => ['required', 'integer', 'distinct', Rule::in(array_keys(config('sdg')))],
            'smart_city_pillars' => ['nullable', 'array', 'list', 'max:2'],
            'smart_city_pillars.*' => ['required', 'string', 'distinct', Rule::in(array_keys(config('dss.bidang_smart_city')))],
        ];
    }
}
