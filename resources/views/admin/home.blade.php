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
                    <td>아이디</td>
                    <td>휴대폰 번호</td>
                    <td>가입일자</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr id="user_{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ count($user->cards) }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_address }}</td>
                        <td>{{ date('y.m.d', strtotime($user->created_at)) }}</td>
                        <td>
                            <button type="button" class="btn btn-success w-55px" onclick="cardView({{ $user->cards }})">보기</button>
                            <button type="button" class="btn btn-primary w-55px" onclick="detail({{ $user->id }})">수정</button>
                            <button type="button" class="btn btn-danger w-55px" onclick="deleted({{ $user->id }})">삭제</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="w-100 d-flex justify-content-center">
                {{ $users->links() }}
            </div>

            <div id="detailView" class="d-none">
                <form method="post" name="formDetail" id="formDetail">
                <div class="pl-3 mb-1">상세정보</div>
                <div id="detail" class="border border-secondary p-3"></div>
                </form>
            </div>

        </div>
    </div>

@section('script')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>

        function cardView(cards) {
            if (cards.length < 1) {
                alert('생성된 명함이 없습니다.');
            } else {
                axios.get('/admin/users/card/' + cards[0].id)
                    .then(function (response) {
                        window.open('/cards/' + response.data.card.id, '_blank');
                    })
                    .catch(function (errors) {
                        alert('명함 불러오기에 실패했습니다.');
                    })
            }
        }
        function deleted(id) {
            if (confirm('정말 삭제하시겠습니까?')) {
                axios.post('/admin/users/delete', {id: id})
                    .then(function (response) {
                        alert('삭제되었습니다.');
                        location.reload();
                    })
                    .catch(function (errors) {
                        if (errors.response.data.message) {
                            alert(errors.response.data.message);
                        } else {
                            alert('삭제에 실패했습니다');
                        }
                    });
            }
        }
        function detail(id) {
            axios.get('/admin/users/' + id + '/detail')
                .then(function (response) {
                    const user = response.data.user;

                    document.getElementById('detail').innerHTML =  userDetailTemplate(user);
                    document.getElementById('detailView').classList.add('d-block');
                })
                .catch(function (error) {

                });
        }

        function updateUser()
        {
            const form = document.getElementById('formDetail');
            const name = form.name.value;
            const password = form.password.value;
            const password_confirmation = form.password_confirmation.value;
            const contact_address = form.contact_address.value;

            const data = {
                id: form.id.value,
                name : name,
                password: password,
                password_confirmation: password_confirmation,
                contact_address: contact_address
            };

            axios.post('/admin/users/update', data)
                .then(function (response) {
                    alert('변경되었습니다.');

                    location.reload();
                })
                .catch(function (errors) {
                    const error = errors.response.data.errors;

                    for (var _error in error) {
                        alert(error[_error][0]);
                        return;
                    }
                })
        }

        function userDetailTemplate(user) {
            return '<div class="detail">' +
                    '<input type="hidden" name="id" value="' + user.id + '">' +
                    '<div>아이디: ' + user.email + '</div>' +
                    '<div>이름: <input type="text" class="form-control d-inline" name="name" id="name" value="' + user.name + '"></div>' +
                    '<div>비밀번호: <input type="password" class="form-control d-inline" name="password" id="password" value=""></div>' +
                    '<div>비밀번호 확인: <input type="password" class="form-control d-inline" name="password_confirmation" id="password_confirmation" value=""></div>' +
                    '<div>휴대번호: <input type="text" class="form-control d-inline" name="contact_address" id="contact_address" value="' + user.contact_address + '"></div>' +
                    '<button type="button" class="btn btn-primary mt-4" onclick="updateUser()">수정하기</button>' +
                '</div>';
        }
    </script>

@endsection
@endsection
