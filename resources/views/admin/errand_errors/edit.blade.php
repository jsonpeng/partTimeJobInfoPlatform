@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Errand Error
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($errandError, ['route' => ['errandErrors.update', $errandError->id], 'method' => 'patch']) !!}

                        @include('errand_errors.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection