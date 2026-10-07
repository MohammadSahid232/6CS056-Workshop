@php($editing = $student !== null)

<form action="{{ $editing ? route('students.update', $student) : route('students.store') }}" method="POST">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="field">
            <label for="name">Full name <span class="required">*</span></label>
            <input id="name" name="name" type="text" value="{{ old('name', $student?->name) }}" autocomplete="name" maxlength="255" required>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="email">Email address <span class="required">*</span></label>
            <input id="email" name="email" type="email" value="{{ old('email', $student?->email) }}" autocomplete="email" maxlength="255" required>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="phone">Phone number <span class="required">*</span></label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone', $student?->phone) }}" autocomplete="tel" maxlength="20" required>
            @error('phone') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="date_of_birth">Date of birth</label>
            <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', $student?->date_of_birth?->format('Y-m-d')) }}">
            @error('date_of_birth') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field field-full">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="4" maxlength="500">{{ old('address', $student?->address) }}</textarea>
            @error('address') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="form-actions">
        <button class="button button-primary" type="submit">{{ $editing ? 'Save changes' : 'Create student' }}</button>
        <a class="button button-quiet" href="{{ $editing ? route('students.show', $student) : route('students.index') }}">Cancel</a>
    </div>
</form>
