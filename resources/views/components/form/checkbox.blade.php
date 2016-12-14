<div class="checkbox">
    <label>
        {{ Form::checkbox($name, $value, isset($attributes['checked']) ? $attributes['checked'] : false) }} {{ $label }}
    </label>
</div>