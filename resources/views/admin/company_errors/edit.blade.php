@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Company Error
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($companyError, ['route' => ['companyErrors.update', $companyError->id], 'method' => 'patch']) !!}

                        @include('company_errors.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection