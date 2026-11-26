@props(['name', 'label', 'value' => ''])

<div>
    <label for="{{ $name }}">{{ $label }}</label><br>
    <textarea id="{{ $name }}" name="{{ $name }}" {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <p>{{ $message }}</p>
    @enderror
</div>
<br>
