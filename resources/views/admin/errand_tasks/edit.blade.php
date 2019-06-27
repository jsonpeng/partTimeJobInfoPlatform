@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
 {{--       <div class="box box-primary">
           <div class="box-body"> --}}
               <div class="row">
                   {!! Form::model($errandTask, ['route' => ['errandTasks.update', $errandTask->id], 'method' => 'patch']) !!}

                        @include('admin.errand_tasks.fields')

                   {!! Form::close() !!}
               </div>
       {{--     </div>
       </div> --}}
   </div>
   @include('admin.partials.imagemodel')
   @include('layouts.js')
@endsection

@section('scripts')
<script type="text/javascript">
  function errandImage(id){
        $('iframe#image').attr('src', '/filemanager/dialog.php?type=1&field_id=' + id);
            console.log(id);
  }

  function deletePic(id){
    $('#errand_image_'+id).remove();
  }
</script>
@endsection