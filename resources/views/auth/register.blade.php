@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}
                                    <span class="text-danger align-middle">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Password') }} (8글자 이상)
                                    <span class="text-danger align-middle">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}
                                    <span class="text-danger align-middle">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}
                                    <span class="text-danger align-middle">*</span>
                                </label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="gender" class="col-md-4 col-form-label text-md-right">성별
                                    <span class="text-danger align-middle">*</span>
                                </label>

                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <span class="gender_txt">남</span><input type="radio" class="form-check-input gender_input @error('gender') is-invalid @enderror"
                                                name="gender" value="M" required {{ old('gender') === 'M' || empty(old('gender')) ? 'checked' : '' }}>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <span class="gender_txt">여</span><input type="radio" class="form-check-input gender_input @error('gender') is-invalid @enderror"
                                                name="gender" value="F" required {{ old('gender') === 'F' ? 'checked' : '' }}>
                                    </div>
                                    @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <div class="form-group row">
                                    <label for="contact_address" class="col-md-4 col-form-label text-md-right">
                                        {{ __('Phone Number') }}
                                        <span class="text-danger align-middle">*</span>
                                    </label>

                                    <div class="col-md-6">
                                        <input id="contact_address" type="tel" class="form-control @error('contact_address') is-invalid @enderror" name="contact_address" value="{{ old('contact_address') }}" required autocomplete="contact_address">
                                    </div>
                                </div>

                                @error('contact_address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <label for="position"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                                <div class="col-md-6">
                                    <input id="position" type="text"
                                           class="form-control @error('position') is-invalid @enderror" name="position"
                                           value="{{ old('position') }}" autocomplete="position">

                                    @error('position')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="company_name"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Company Name') }}</label>

                                <div class="col-md-6">
                                    <input id="company_name" type="text"
                                           class="form-control @error('company_name') is-invalid @enderror"
                                           name="company_name"
                                           value="{{ old('company_name') }}" autocomplete="company_name">
                                </div>

                                @error('company_name')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
                                <div class="col-md-6">
                                    <input id="address" type="text"
                                           class="form-control @error('address') is-invalid @enderror" name="address"
                                           onclick="openAddress()"
                                           value="{{ old('address') }}" autocomplete="address" autofocus readonly>

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-dark">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('script')
        <script>
            function openAddress() {
                new daum.Postcode({
                    oncomplete: function (data) {
                        document.getElementById("address").setAttribute("value", data.address);
                    }
                }).open();
            }
        </script>
    @endsection
@endsection
