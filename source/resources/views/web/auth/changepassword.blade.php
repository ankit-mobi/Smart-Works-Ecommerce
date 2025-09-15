@extends('web.layout.app')
<style>
    body {
        background-color: #EBEBEB !important;
    }
</style>

@section('content')
<div class="container"
    style="background-color: white;border-radius: 10px;width: 73%;padding: 40px 40px 30px 40px;margin-top: 160px;">

    <div class="row">
        <div class="col-sm-12">
            <h3>Change Password</h3>
        </div>
    </div>

    <form class="login100-form validate-form" method="POST" action="{{ route('reset_password') }}">   {{-- action="{{ route('password.change.submit') }}"--}}
        @csrf
        <div class="row" style="margin-top: 20px;">
            <div class="col-lg-12">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (count($errors) > 0)
                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{$errors->first()}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    @endif
                @endif
            </div>
            
            <div class="col-sm-7" style="border-right: 1px solid #CCCCCC">
                <label for="new_password" style="color: gray;">New Password <span style="color: red;">*</span></label>
                <div class="input-group mb-3">
                    <input type="password" name="user_password" style="max-width: 84%;" class="form-control" id="user_password"
                        placeholder="Enter new password" required>
                </div>

                <label for="confirm_password" style="color: gray;">Confirm Password <span style="color: red;">*</span></label>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" style="max-width: 84%;" class="form-control" id="confirm_password"
                        placeholder="Confirm new password" required>
                </div>

                <input type="hidden" name="user_phone" value="{{ $user_phone }}">

                <button type="submit" class="btn"
                    style="background-color: #FB641B;color: white;width: 45%;font-size: 17px;margin-top: 20px;">Change Password</button>
            </div>

            <div class="col-sm-5">
                </div>
        </div>
    </form>
</div>
@endsection