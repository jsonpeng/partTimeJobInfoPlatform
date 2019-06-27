<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', '学校ID:') !!}
    <p>{!! $school->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', '学校名称:') !!}
    <p>{!! $school->name !!}</p>
</div>

<!-- Province Field -->
<div class="form-group">
    {!! Form::label('province', '省份:') !!}
    <p>{!! $school->province !!}</p>
</div>

<!-- City Field -->
<div class="form-group">
    {!! Form::label('city', '城市:') !!}
    <p>{!! $school->city !!}</p>
</div>

<!-- District Field -->
<div class="form-group">
    {!! Form::label('district', '区域:') !!}
    <p>{!! $school->district !!}</p>
</div>

<!-- Address Field -->
<div class="form-group">
    {!! Form::label('address', '地址:') !!}
    <p>{!! $school->address !!}</p>
</div>

<!-- Lon Field -->
<div class="form-group">
    {!! Form::label('lon', '经度:') !!}
    <p>{!! $school->lon !!}</p>
</div>

<!-- Lat Field -->
<div class="form-group">
    {!! Form::label('lat', '纬度:') !!}
    <p>{!! $school->lat !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', '创建时间:') !!}
    <p>{!! $school->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', '更新时间:') !!}
    <p>{!! $school->updated_at !!}</p>
</div>

