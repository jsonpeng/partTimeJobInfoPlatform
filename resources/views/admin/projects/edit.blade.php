@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑兼职
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
    {{--    <div class="box box-primary">
           <div class="box-body"> --}}
               <div class="row">
                   {!! Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'patch']) !!}

                        @include('admin.projects.fields')

                   {!! Form::close() !!}
               </div>
       {{--     </div>
       </div> --}}
   </div>
    @include('admin.partials.imagemodel')
    @include('layouts.js')
@endsection

@include('admin.projects.js')
