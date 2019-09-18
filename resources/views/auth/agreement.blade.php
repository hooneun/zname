@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">이용약관 동의</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('checkAgreement') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="form-check">
                                    <input type="checkbox" class="d-inline w-auto" id="all_check">
                                    <label for="all_check">모두 동의 합니다.</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-check">
                                    <input type="checkbox" class="d-inline w-auto" id="service_agreement" name="service_agreement" required>
                                    <label for="service_agreement">서비스 이용약관 동의 (필수) <a href="{{ route('serviceAgreement') }}" class="ml-2"><i class="fas fa-arrow-right text-dark"></i></a></label>

                                    @error('service_agreement')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-check">
                                    <input type="checkbox" class="d-inline w-auto" id="personal_agreement" name="personal_agreement" required>
                                    <label for="personal_agreement">개인정보 이용약관 동의 (필수) <a class="ml-2" href="{{ route('personalAgreement') }}"><i class="fas fa-arrow-right text-dark"></i></a></label>

                                    @error('personal_agreement')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-dark">
                                        동의하고 계속 진행합니다.
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const SERVICE_AGREEMENT = document.getElementById('service_agreement');
        const PERSONAL_AGREEMENT = document.getElementById('personal_agreement');
        const ALL_CHECK = document.getElementById('all_check');

        ALL_CHECK.onclick = function () {
            if (!this.checked) {
                SERVICE_AGREEMENT.checked = false;
                PERSONAL_AGREEMENT.checked = false;
            } else {
                SERVICE_AGREEMENT.checked = true;
                PERSONAL_AGREEMENT.checked = true;
            }
        };

        SERVICE_AGREEMENT.onchange = function () {
            if (this.checked && PERSONAL_AGREEMENT.checked) {
                ALL_CHECK.checked = true;
            } else {
                ALL_CHECK.checked = false;
            }
        };

        PERSONAL_AGREEMENT.onchange = function () {
            if (this.checked && SERVICE_AGREEMENT.checked) {
                ALL_CHECK.checked = true;
            } else {
                ALL_CHECK.checked = false;
            }
        }
    </script>
@endsection