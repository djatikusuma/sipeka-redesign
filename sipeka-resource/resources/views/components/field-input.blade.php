<div class="fv-row mb-8">
    <label for="{{ $fieldName }}" class="{{ $required }} fw-bold form-label">{{ $labelName }}</label>
    <input type="text" class="form-control" name="{{ $fieldName }}" value="{{ old($fieldName) ?? $model ?? '' }}" placeholder="{{ $placeholder }}" autocomplete="off" />
    @error($fieldName)
    <div class="fv-plugins-message-container invalid-feedback">
        <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
    </div>
    @enderror
</div>
