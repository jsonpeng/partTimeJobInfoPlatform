@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑学校
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($school, ['route' => ['schools.update', $school->id], 'method' => 'patch']) !!}

                        @include('admin.schools.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
   @include('layouts.js')
@endsection