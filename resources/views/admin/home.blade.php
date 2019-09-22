@extends('admin.app')

@section('content')
    <div class="container" id="js-admin_home">
        <div class="row">
            <div class="pl-3">회원 정보 ({{ $users->total() }}명)</div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>#</td>
                    <td>회원이름</td>
                    <td>명함</td>
                    <td>가입일자</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->cards_count }}</td>
                        <td>{{ date('y.m.d', strtotime($user->created_at)) }}</td>
                        <td>
                            <button type="button" class="btn btn-primary w-55px" @click="detail(user.id)">보기</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="w-100 d-flex justify-content-center">
                {{ $users->links() }}
            </div>

        </div>
    </div>

@section('script')
@endsection
@endsection
