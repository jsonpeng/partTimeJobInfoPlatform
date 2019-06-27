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
                    {!! Form::open(['route' => 'companyErrors.store']) !!}

                        @include('company_errors.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
