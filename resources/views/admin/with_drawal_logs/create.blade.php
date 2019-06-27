@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            With Drawal Log
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'withDrawalLogs.store']) !!}

                        @include('admin.with_drawal_logs.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
