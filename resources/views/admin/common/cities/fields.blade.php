<!-- Name Field -->
<div class="form-group col-sm-12 col-xs-12">
    {!! Form::label('name', '地区名:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

    {!! Form::hidden('pid', $pid, ['class' => 'form-control']) !!}
    {!! Form::hidden('level', $level, ['class' => 'form-control']) !!}

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('cities.index') !!}" class="btn btn-default">返回</a>
</div>
