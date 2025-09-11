@extends('web.layout.app')

@section('content')

<div class="container" style="background-color: white;border-radius: 10px;width: 73%;padding: 40px 40px 30px 40px;margin-top: 160px;">

  <div class="row">
    <div class="col-sm-12">
      <h3>Forgot Password</h3>
    </div>
  </div>
  
  
  <form class="login100-form validate-form" method="POST" action="{{ route('forgotPassword') }}"> {{-- route('forgotPassword') --}}
    {{ csrf_field() }}
    <div class="row" style="margin-top: 20px;">
      <div class="col-sm-7" style="border-right: 1px solid #CCCCCC">
        <div class="col-lg-12">
          @if (session()->has('success'))
            <div class="alert alert-success">
              @if(is_array(session()->get('success')))
                <ul>
                  @foreach (session()->get('success') as $message)
                    <li>{{ $message }}</li>
                  @endforeach
                </ul>
              @else
                {{ session()->get('success') }}
              @endif
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

        <p style="color: gray;">Enter your registered phone number to reset your password.</p>
        
        <label for="user-phone" style="color: gray;">User Phone<span style="color: red;">*</span></label>
        <div class="input-group mb-3">
          <input name="user_phone" type="number" style="max-width: 84%;" class="form-control" id="user-phone" aria-describedby="basic-addon3" placeholder="9999999999" maxlength="10" required>
        </div>

        <button class="btn" style="background-color: #FB641B;color: white;width: 48%;font-size: 17px;margin-top: 20px;display: inline-block;" type="submit">Submit</button>

      </div>

      <div class="col-sm-5">
        <div class="row">
            <div class="col-sm-3" style="margin-top: 40px;">
                <span style="float: right;color: #B8B8B8;font-size: 30px;"><i class="fas fa-truck"></i></span>
            </div>
            <div class="col-sm-9" style="margin-top: 40px;">
                <p style="color: #B8B8B8;font-weight: 500">Manage your orders</p>
                <p style="color: #B8B8B8;font-weight: 500;margin-top: -15px;">
                    Easily track orders, Create returns.
                </p>
            </div>
            <div class="col-sm-3" style="margin-top: 20px;">
                <span style="float: right;color: #B8B8B8;font-size: 30px;"><i class="fas fa-bell"></i></span>
            </div>
            <div class="col-sm-9" style="margin-top: 20px;">
                <p style="color: #B8B8B8;font-weight: 500">Get Notifications</p>
                <p style="color: #B8B8B8;font-weight: 500;margin-top: -15px;">
                    Stay updated on your orders.
                </p>
            </div>
            <div class="col-sm-3" style="margin-top: 20px;">
                <span style="float: right;color: #B8B8B8;font-size: 30px;"><i class="fas fa-thumbs-up"></i></span>
            </div>
            <div class="col-sm-9" style="margin-top: 20px;">
                <p style="color: #B8B8B8;font-weight: 500">Fast & Secure</p>
                <p style="color: #B8B8B8;font-weight: 500;margin-top: -15px;">
                    Enjoy a seamless checkout experience.
                </p>
            </div>
        </div>
      </div>
    </div>
  </form>
</div>
<br>

@endsection