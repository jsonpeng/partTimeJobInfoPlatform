<!-- Name Field -->

{{-- <div class="form-group col-sm-12">
    {!! Form::label('name', '姓名:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div> --}}

<div class="form-group col-sm-12">
        {!! Form::label('image', '头像:') !!}

        <div class="input-append">
            {!! Form::text('head_image', null, ['class' => 'form-control', 'id' => 'image1']) !!}
            <a data-toggle="modal" href="javascript:;" data-target="#myModal" class="btn" type="button" onclick="changeImageId('image1')">选择图片</a>
            <img src="@if($users) {{$users->head_image}} @endif" style="max-width: 100%; max-height: 150px; display: block;">
        </div>

</div>

<div class="form-group col-sm-12">
    {!! Form::label('nickname', '昵称:') !!}
    {!! Form::text('nickname', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-12">
    {!! Form::label('mobile', '手机号:') !!}
    {!! Form::number('mobile', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('credits', '信誉积分:') !!}
    {!! Form::number('credits', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('user_money', '账户余额:') !!}
    {!! Form::number('user_money', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('type', '用户类型:') !!}
    <select name="type" class="form-control">
        <option value="个人" @if(!empty($users) && $users->type=='个人') selected="selected" @endif>个人</option>
        <option value="企业" @if(!empty($users) && $users->type=='企业') selected="selected" @endif>企业</option>
    </select>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">返回</a>
</div>





