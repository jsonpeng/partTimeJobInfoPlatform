@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">企业管理</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('caompanies.create') !!}">添加</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

          <!--查询搜索框-->
         <div class="box box-default box-solid mb10-xs @if(!$tools) collapsed-box @endif">
            <div class="box-header with-border">
              <h3 class="box-title">查询</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-{!! !$tools?'plus':'minus' !!}"></i></button>
              </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <form id="projects_search">
                    <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <label for="order_delivery">企业名称</label>
                       <input type="text" class="form-control" name="name" placeholder="项目名称" @if (array_key_exists('name', $input))value="{{$input['name']}}"@endif>
                    </div>

                    <div class="form-group col-lg-4 col-md-9 col-sm-12 col-xs-12">
                        <label for="order_pay">地域城市</label>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr0-xs">  
                                <select name="diyu" class="form-control level01" >
                                    <option value="0">请选择地域</option>
                                 {{--    <?php $i=0;?> --}}
                                    @foreach ($diyu as $k => $v)
                                   {{--  <?php $i++;?> --}}
                                    <option value="{!! $k !!}" @if(array_key_exists('diyu',$input) && $input['diyu']== $k) selected="selected" @endif>{!! $k !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr0-xs">    
                                {!! Form::select('cities',$cities,$level02 , ['class' => 'form-control level02']) !!}
                             </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-1 col-md-1 hidden-xs hidden-sm" style="padding-top: 25px;">
                        <button type="submit" class="btn btn-primary pull-right " onclick="search()">查询</button>
                    </div>
                    <div class="form-group col-xs-6 visible-xs visible-sm" >
                        <button type="submit" class="btn btn-primary pull-left " onclick="search()">查询</button>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        <!--/查询搜索框-->


        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.caompanies.table')
            </div>
        </div>
         <div class="text-center">
             <div class="tc"><?php echo $caompanies->appends($input)->render(); ?></div>
         </div>
    </div>
@endsection

@section('scripts')
<script>
    $(function(){
        $('.level01').change(function(){
            $('.level02').empty();
            var diyu=$(this).val();

            if(diyu==0){
                  $('.level02').empty();
                  $('.level02').append('<option value="0">请选择地区</option>');
                  return false;
            }
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/ajax/diyu/getAjaxSelect/"+diyu,
                type:"POST",
                success: function(data) {
                         if(data.code==0){
                            $('.level02').append(data.message);
                         }else{
                            $('.level02').empty();
                         }
                }
            });

        });
    });
</script>
@endsection