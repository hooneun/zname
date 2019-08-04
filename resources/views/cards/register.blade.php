@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="">
                    @csrf
                    <div class="card">
                        {{--<div class="card-header"></div>--}}
                        <div class="card-body">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection