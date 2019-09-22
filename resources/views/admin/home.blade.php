@extends('admin.app')

@section('content')
    <div class="container" id="js-admin_home">
        <div class="row">
            <div class="pl-3">회원 정보 (@{{ users.total }}명)</div>
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
                <tr v-for="user in users.data">
                    <td>@{{ user.id }}</td>
                    <td>@{{ user.name }}</td>
                    <td>@{{ user.cards_count }}</td>
                    <td>@{{ dateFormat(user.created_at) }}</td>
                    <td>
                        <button type="button" class="btn btn-primary w-55px" @click="detail(user.id)">보기</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        const admin = new Vue({
            el: '#js-admin_home',
            data: {
                users: {}
            },
            methods: {
                getUsers: function () {
                    const self = this;

                    axios.get('/admin/users')
                        .then(function (response) {
                            self.users = response.data.users;
                        })
                },
                dateFormat: function (date) {
                    return date.substr(2, 8).replace(/-/gi, '.');
                },
                detail: function (id) {
                    const self = this;
                    axios.get('/admin/users/' + id + '/detail')
                        .then(function (response) {

                        })
                }
            },
            mounted() {
                this.getUsers();
            }
        })
    </script>
@endsection
@endsection
