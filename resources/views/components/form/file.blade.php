<div class="form-group">
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
    {{ Form::file($name, array_merge(['class' => 'form-control'])) }}
</div>