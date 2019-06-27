<div class="form-group col-sm-8">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">企业详情</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
                <!-- Name Field -->
                <div class="form-group">
                    {!! Form::label('name', '企业名称:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    {!! Form::hidden('user_id',null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('contact_man', '联系人姓名:') !!}
                    {!! Form::text('contact_man', null, ['class' => 'form-control']) !!}
                </div>
                
                <!-- Intro Field -->
                <div class="form-group">
                    {!! Form::label('intro', '企业介绍:') !!}
                    {!! Form::textarea('intro', null,['class' => 'form-control']) !!}
                </div>

                 <div class="form-group">
                      <section class="content-header" style="height: 50px; padding: 0; padding-top: 15px;">
                      <h1 class="pull-left" style="font-size: 14px; font-weight: bold; line-height: 34px;padding-bottom: 0px;">企业展示图片</h1>

                       <h3 class="pull-right" style="margin: 0">
                                <input type="hidden" name="addimage" value="" id="company_image">
                                <div class="pull-right" style="margin: 0">
                                    <a data-toggle="modal" href="javascript:;" data-target="#myModal" class="btn btn-primary" type="button" onclick="companyImage('company_image')">添加企业展示图片</a>
                                </div>
                        </h3>
                    </section>

                    <div class="images" style="display:@if(count($images)) block @else none @endif;">
                            <?php $i=0;?>
                            @foreach ($images as $image)
                            <div class="image-item" id="company_image_{{ $i }}">
                                <img src="{!! $image->
                                url !!}" alt="" style="max-width: 100%;">
                                <div class="tr">
                                    <div class="btn btn-danger btn-xs" onclick="deletePic({{ $i }})">删除</div>
                                </div>
                                 <input type='hidden' name='company_images[]' value='{!! $image->
                                url !!}'>
                            </div>
                            <?php $i++;?>
                            @endforeach
                    </div>
                </div>

               <div class="form-group group">
                    <select name="province" id="province" >
                        <option value="0" @if(empty($caompany)) selected="selected" @endif>请选择省份</option>
                        @foreach($cities_level1 as $item)
                            <option value="{!! $item->id !!}" @if(!empty($caompany)) @if($caompany->province==$item->id) selected="selected" @endif @endif>{!! $item->name !!}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group group">
                    <select  name="city" id="city">
                            <option value="0" @if(empty($caompany)) selected="selected" @endif>请选择城市</option>
                            @foreach ($cities_level2 as $item)
                                <option value="{!! $item->id !!}" @if(!empty($caompany)) @if($caompany->city==$item->id) selected="selected" @endif @endif>{!! $item->name !!}</option>
                            @endforeach
                    </select>
                </div>

                <div class="form-group group">
                    <select  name="district"  id="district"  data-type="company">
                            <option value="0" @if(empty($caompany)) selected="selected" @endif>请选择区域</option>
                            @foreach ($cities_level3 as $item)
                                <option value="{!! $item->id !!}" @if(!empty($caompany)) @if($caompany->district==$item->id) selected="selected" @endif @endif>{!! $item->name !!}</option>
                            @endforeach
                    </select>
                </div>

                <!-- Detail Field -->
                <div class="form-group">
                    {!! Form::label('detail', '详细地址:') !!}
                    {!! Form::text('detail', null, ['class' => 'form-control']) !!}
                    <a class="inline-block pd10" onclick="openMap()">在地图中设定</a>
                </div>

                <!-- Lat Field -->
                <div class="form-group">
                    {!! Form::label('lat', '纬度:') !!}
                    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
                </div>

                <!-- Lon Field -->
                <div class="form-group">
                    {!! Form::label('lon', '精度:') !!}
                    {!! Form::text('lon', null, ['class' => 'form-control']) !!}
                </div>

        </div>
    </div>
</div>


<div class="form-group col-sm-4">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">发布设置</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
                {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('caompanies.index') !!}" class="btn btn-default">返回</a>
          </div>
    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">其他设置</h3>
        </div><!-- /.box-header -->
        <div class="box-body">

                <div class="form-group">
                    {!! Form::label('status', '审核状态:') !!}
                     <select name="status" class="form-control" >
                            <option value="审核中" @if(empty($caompany)) selected="selected" @endif>审核中</option>
                            <option value="通过" @if(!empty($caompany)) @if($caompany->status=='通过') selected="selected" @endif @endif>通过</option>
                            <option value="不通过" @if(!empty($caompany)) @if($caompany->status=='不通过') selected="selected" @endif @endif>不通过</option>
                    </select>
                </div>
                <!-- Mobile Field -->
                <div class="form-group">
                    {!! Form::label('mobile', '电话:') !!}
                    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
                </div>

                <!-- Weixin Field -->
                <div class="form-group">
                    {!! Form::label('weixin', '微信:') !!}
                    {!! Form::text('weixin', null, ['class' => 'form-control']) !!}
                </div>

                <!-- View Field -->
                <div class="form-group">
                    {!! Form::label('view', '浏览量:') !!}
                    {!! Form::number('view', null, ['class' => 'form-control']) !!}
                </div>

                <!-- Collect Field -->
             {{--    <div class="form-group"> --}}
                 {{--    {!! Form::label('collect', '收藏量:') !!} --}}
                    {!! Form::hidden('collect', null, ['class' => 'form-control']) !!}
          {{--       </div> --}}
          </div>
    </div>
</div>