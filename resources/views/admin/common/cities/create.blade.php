@extends('layouts.app')

@section('content')
    <div class="container-fluid" >
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <section class="content-header">
                    <h1>
                       添加地区
                    </h1>
                </section>
                <div class="content pdall0-xs">
                    @include('adminlte-templates::common.errors')
                    <div class="box box-primary mb10-xs form">

                        <div class="box-body">
                            <div class="row">
                                {!! Form::open(['route' => 'cities.store']) !!}

                                    @include('admin.common.cities.fields')

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


