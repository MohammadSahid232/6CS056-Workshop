@php($editing = $course !== null)

<form action="{{ $editing ? route('courses.update', $course) : route('courses.store') }}" method="POST">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="field field-full">
            <label for="name">Course name <span class="required">*</span></label>
            <input id="name" name="name" type="text" value="{{ old('name', $course?->name) }}" maxlength="255" required>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field field-full">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" maxlength="5000">{{ old('description', $course?->description) }}</textarea>
            @error('description') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="duration">Duration (weeks) <span class="required">*</span></label>
            <input id="duration" name="duration" type="number" value="{{ old('duration', $course?->duration) }}" min="1" step="1" required>
            @error('duration') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="fee">Fee <span class="required">*</span></label>
            <input id="fee" name="fee" type="number" value="{{ old('fee', $course?->fee) }}" min="0" step="0.01" inputmode="decimal" required>
            @error('fee') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="difficulty">Difficulty <span class="required">*</span></label>
            <select id="difficulty" name="difficulty" required>
                <option value="">Choose a level</option>
                @foreach (\App\Models\Course::DIFFICULTIES as $level)
                    <option value="{{ $level }}" @selected(old('difficulty', $course?->difficulty) === $level)>{{ $level }}</option>
                @endforeach
            </select>
            @error('difficulty') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field field-full">
            <input type="hidden" name="is_active" value="0">
            <label class="check-field" for="is_active">
                <input id="is_active" name="is_active" type="checkbox" value="1" @checked((bool) old('is_active', $course?->is_active ?? true))>
                <span>Course is currently provided</span>
            </label>
            @error('is_active') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="form-actions">
        <button class="button button-primary" type="submit">{{ $editing ? 'Save changes' : 'Create course' }}</button>
        <a class="button button-quiet" href="{{ $editing ? route('courses.show', $course) : route('courses.index') }}">Cancel</a>
    </div>
</form>
