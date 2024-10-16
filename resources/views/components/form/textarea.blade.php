@props([
'name', 'value' => '', 'label' => false
])

@if($label)
<label for="{{ $name }}">{{ $label }}</label>
@endif

<textarea id="{{ $name }}" name="{{ $name }}" {{ $attributes->class([
        'form-control',
        'is-invalid' => $errors->has($name)
    ]) }}>{{ old($name, $value) }}</textarea>