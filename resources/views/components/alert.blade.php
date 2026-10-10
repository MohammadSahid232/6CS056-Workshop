@props(['type' => 'success'])

@if (session($type))
    <div class="alert alert-{{ $type }}" role="alert">
        {{ session($type) }}
    </div>
@endif
