@props(['record'])

<input type="hidden" name="dss_classification_form" value="1">
<p class="form-text">Pilih minimal satu SDG atau satu pilar Smart City.</p>
<div class="row g-3 mb-3">
    @foreach (['sdgs' => ['Tujuan Pembangunan Berkelanjutan (SDG)', config('sdg')], 'smart_city_pillars' => ['Pilar Smart City', \App\Models\AktivitasDosen::bidangSmartCityList()]] as $field => [$label, $options])
        @php
            $fallback = $record->{$field} ?? ($field === 'smart_city_pillars' && $record->bidang_smart_city ? [$record->bidang_smart_city] : []);
            $selected = (array) old($field, old('dss_classification_form') ? [] : $fallback);
        @endphp
        <fieldset class="col-md-6" data-dss-limit>
            <legend class="form-label fs-6">{{ $label }}</legend>
            <p class="form-text mt-0">Maksimal 2 pilihan.</p>
            <div class="border rounded p-2 overflow-auto" style="max-height: 13rem">
                @foreach ($options as $key => $name)
                    <label class="d-flex align-items-start gap-2 mb-1">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="{{ $field }}[]" value="{{ $key }}"
                            @checked(in_array((string) $key, array_map('strval', array_filter($selected, 'is_scalar')), true))>
                        <span>{{ $field === 'sdgs' ? $key . '. ' : '' }}{{ $name }}</span>
                    </label>
                @endforeach
            </div>
            @foreach ($errors->get($field) as $message)
                <p class="text-danger small mb-0">{{ $message }}</p>
            @endforeach
            @foreach ($errors->get($field . '.*') as $messages)
                @foreach ($messages as $message)
                    <p class="text-danger small mb-0">{{ $message }}</p>
                @endforeach
            @endforeach
        </fieldset>
    @endforeach
</div>
@once
<script>
    document.querySelectorAll('[data-dss-limit]').forEach(function (group) {
        const boxes = Array.from(group.querySelectorAll('input[type="checkbox"]'));
        function updateLimit() {
            const full = boxes.filter(box => box.checked).length >= 2;
            boxes.forEach(box => { box.disabled = full && !box.checked; });
        }
        group.addEventListener('change', updateLimit);
        updateLimit();
    });
</script>
@endonce
