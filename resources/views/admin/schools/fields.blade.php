<!-- Name Field -->
<div class="form-group col-sm-8">
    {!! Form::label('name', '学校名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Province Field -->
<div class="form-group col-sm-8">
    {!! Form::label('province', '省份:') !!}
    {!! Form::text('province', null, ['class' => 'form-control']) !!}
</div>

<!-- City Field -->
<div class="form-group col-sm-8">
    {!! Form::label('city', '城市:') !!}
    {!! Form::text('city', null, ['class' => 'form-control']) !!}
</div>

<!-- District Field -->
<div class="form-group col-sm-8">
    {!! Form::label('district', '区域:') !!}
    {!! Form::text('district', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-8">
    {!! Form::label('address', '学校地址:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Lon Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lon', '纬度:') !!}
    {!! Form::text('lon', null, ['class' => 'form-control']) !!}
</div>

<!-- Lat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lat', '经度:') !!}
    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('schools.index') !!}" class="btn btn-default">返回</a>
</div>
