@php
    $course = $course ?? new \App\Models\Course(['is_active' => true]);
@endphp

<x-form-input name="name" label="Name" :value="$course->name" required maxlength="255" />
<x-form-textarea name="description" label="Description" :value="$course->description" />
<x-form-input name="duration" label="Duration (weeks)" type="number" min="1" :value="$course->duration" required />
<x-form-input name="fee" label="Fee" type="number" step="0.01" min="0" :value="$course->fee" required />

<div class="form-group">
    <label for="difficulty">Difficulty</label>
    <select id="difficulty" name="difficulty" required>
        @foreach(['Easy', 'Medium', 'Hard'] as $level)
            <option value="{{ $level }}" @selected(old('difficulty', $course->difficulty) === $level)>
                {{ $level }}
            </option>
        @endforeach
    </select>
    @error('difficulty')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group checkbox">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $course->is_active))>
    <label for="is_active">Active</label>
</div>
