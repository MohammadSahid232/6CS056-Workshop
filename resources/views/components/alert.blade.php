@props(['type' => 'success'])

@if (session($type))
    <div role="alert">
        <strong>{{ session($type) }}</strong>
    </div>
@endif
