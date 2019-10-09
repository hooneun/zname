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
                    <tr id="user_{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->cards_count }}</td>
                        <td>{{ date('y.m.d', strtotime($user->created_at)) }}</td>
                        <td>
                            <button type="button" class="btn btn-primary w-55px" onclick="detail({{ $user->id }})">보기</button>
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
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        function detail(id) {
            axios.get('/admin/users/' + id + '/detail')
                .then(function (response) {
                    const user = response.data.user;
                    const detailElement = document.getElementsByClassName('detail');
                    const detailElementLength = detailElement.length;

                    if (detailElementLength > 0) {
                        for (let i = 0; i < detailElementLength; i++) {
                            detailElement[i].remove();
                        }
                    }

                    document.getElementById('user_' + id).insertAdjacentHTML('afterend', userDetailTemplate(user))
                })
                .catch(function (error) {

                });
        }

        function userDetailTemplate(user) {
            return '<tr class="detail">' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td></td>' +
                '</tr>';
        }
    </script>

@endsection
@endsection
