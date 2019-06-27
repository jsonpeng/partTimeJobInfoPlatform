@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Feed Back
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('feed_back.show_fields')
                    <a href="{!! route('feedBack.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
