@props(['name', 'label', 'type' => 'text', 'value' => ''])

<div>
    <label for="{{ $name }}">{{ $label }}</label><br>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}" {{ $attributes }}>
    @error($name)
        <p>{{ $message }}</p>
    @enderror
</div>
<br>
