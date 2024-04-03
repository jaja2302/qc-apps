@extends('AdminLayout.head')


@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('User Profile') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="img-thumbnail">
                        </div>
                        <div class="col-md-9">
                            <table class="table table-striped">
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            </table>
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">{{ __('Edit Profile')
                                }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection