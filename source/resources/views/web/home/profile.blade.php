@extends('web.layout.app')

@section('content')
    <br>
    {{-- alert handling --}}
    <div class="col-lg-12">
        @if (session()->has('success'))
            <div class="alert alert-success">
                @if(is_array(session()->get('success')))
                    <ul>
                        @foreach (session()->get('success') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </ul>
                @else
                    {{ session()->get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
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



    <div class="container py-5">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center py-4">
                        {{-- Profile Image --}}
                        @if($user->user_image)
                            <img src="{{ asset($user->user_image) }}" alt="Profile Image" class="rounded-circle mb-3 shadow-sm"
                                style="width:100px; height:100px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm"
                                style="width:100px; height:100px; margin:auto;">
                                <i class="bi bi-person fs-1 text-secondary"></i>
                            </div>
                        @endif
                        <h6 class="fw-bold">{{ $user->user_name }}</h6>
                        <p class="text-muted small mb-0">Hello</p>
                    </div>
                </div>

                {{-- sidebar section --}}
                <div class="list-group shadow-sm" id="profile-tabs" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="account-tab" data-bs-toggle="list"
                        href="#personal-info" role="tab" aria-controls="personal-info">
                        <i class="bi bi-person me-2"></i> My Account
                    </a>
                    <a class="list-group-item list-group-item-action" id="orders-tab" data-bs-toggle="list"
                        href="#my-orders" role="tab" aria-controls="my-orders">
                        <i class="bi bi-bag me-2"></i> My Orders
                    </a>
                    <a class="list-group-item list-group-item-action" id="returns-tab" data-bs-toggle="list"
                        href="#returns-cancel" role="tab" aria-controls="returns-cancel">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Returns & Cancel
                    </a>
                    <a class="list-group-item list-group-item-action" id="wishlist-tab" data-bs-toggle="list"
                        href="#my-wishlist" role="tab" aria-controls="my-wishlist">
                        <i class="bi bi-heart me-2"></i> My Wishlist
                    </a>
                    <a class="list-group-item list-group-item-action" id="payment-tab" data-bs-toggle="list"
                        href="#my-payment" role="tab" aria-controls="my-payment">
                        <i class="bi bi-credit-card me-2"></i> Payment
                    </a>
                    <a class="list-group-item list-group-item-action" id="password-tab" data-bs-toggle="list"
                        href="#change-password" role="tab" aria-controls="change-password">
                        <i class="bi bi-lock me-2"></i> Change Password
                    </a>
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="col-md-9">
                <div class="tab-content" id="nav-tabContent">

                    {{-- Personal Information Tab Content --}}
                    <div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="account-tab">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Personal Information</h5>
                                <a href="#" class="text-primary" data-bs-toggle="collapse" data-bs-target="#updateForm"
                                    aria-expanded="false" aria-controls="updateForm">
                                    <i class="bi bi-pencil-square me-1"></i> Change Profile Information
                                </a>
                            </div>
                            <div class="card-body">
                                {{-- Profile Details --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Name</label>
                                    <input type="text" class="form-control" value="{{ $user->user_name }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="text" class="form-control" value="{{ $user->user_email }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone</label>
                                    <input type="text" class="form-control" value="{{ $user->user_phone ?? 'Not Added' }}"
                                        disabled>
                                </div>

                                {{-- Update Form (Collapsible) --}}
                                <div class="collapse mt-4" id="updateForm">
                                    <form action="{{ route('profile.update', $user->user_id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="username"
                                                value="{{ $user->user_name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="useremail"
                                                value="{{ $user->user_email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="phone"
                                                value="{{ $user->user_phone }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Profile Image</label>
                                            <input type="file" class="form-control" name="userimage">
                                        </div>
                                        <button type="submit" class="btn btn-primary"><i
                                                class="bi bi-check-circle me-1"></i> Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>




                        {{-- Addresses Tab Content --}}

                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">My Addresses</h5>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#addAddressModal">
                                    <i class="bi bi-plus-circle me-1"></i> Add New Address
                                </button>
                            </div>
                            <div class="card-body">
                                {{-- Correctly check if the collection is NOT empty --}}
                                @if($addresses->isNotEmpty())
                                    {{-- Loop through each address in the collection --}}
                                    @foreach($addresses as $address)
                                        <div class="address-item mb-3 p-3 border rounded">
                                            <p class="fw-bold mb-1">{{ $address->receiver_name }}</p>
                                             <p class="fw-bold mb-1">{{ $address->receiver_phone }}</p>
                                            <p class="mb-1">
                                                {{ $address->house_no }}, {{ $address->society }}, {{ $address->landmark }} <br>
                                                {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                                            </p>
                                            {{-- Check if the current address is the selected one and display the badge --}}
                                            @if($address->select_status == 1)
                                                <span class="badge bg-success">Selected</span>
                                            @else
                                            <button>select</button>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">No addresses found. Please <a href="{{ route('profile') }}">add/select
                                            an address</a>.</p>
                                @endif
                            </div>

                            {{-- Add Address Modal (remains the same) --}}
                            <div class="modal fade" id="addAddressModal" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('address.add') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add New Address</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row g-3">

                                                <div class="col-md-6">
                                                    <label class="form-label">Pincode</label>
                                                    <input type="text" name="pincode" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">House No., Buidling Name</label>
                                                    <input type="text" name="house_no" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">City</label>
                                                    <input type="text" name="city" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">State</label>
                                                    <input type="text" name="state" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Society, Area, Colony</label>
                                                    <input type="text" name="society" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Landmark(Optional)</label>
                                                    <input type="text" name="landmark" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="receiver_name" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">10-digit mobile number</label>
                                                    <input type="text" name="receiver_phone" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Latitude</label>
                                                    <input type="text" name="lat" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Longitude</label>
                                                    <input type="text" name="lng" class="form-control">
                                                </div>

                                                {{-- <input type="hidden" name="lat" class="form-control">
                                                <input type="hidden" name="lng" class="form-control"> --}}



                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Address</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- My Orders Tab Content --}}
                <div class="tab-pane fade" id="my-orders" role="tabpanel" aria-labelledby="orders-tab">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">My Orders</h5>
                        </div>
                        <div class="card-body">
                            @if($orders->count())
                                @foreach($orders as $order)
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0">Order ID: #{{ $order->order_id }}</h6>
                                                    <small class="text-muted">{{ $order->order_date }}</small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-primary fs-6">{{ $order->order_status }}</span>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Total Price:</strong>
                                                        ${{ number_format($order->total_price, 2) }}</p>
                                                    <p class="mb-1"><strong>Payment:</strong> {{ $order->payment_method }}</p>
                                                </div>
                                                <div class="col-md-6 text-md-end">
                                                    <a href="{{ route('order.details', $order->order_id) }}"
                                                        class="btn btn-sm btn-outline-info mt-2 mt-md-0">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info text-center">
                                    You have not placed any orders yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>



                {{-- Placeholder for other tabs --}}
                <div class="tab-pane fade" id="returns-cancel" role="tabpanel" aria-labelledby="returns-tab">...</div>
                <div class="tab-pane fade" id="my-wishlist" role="tabpanel" aria-labelledby="wishlist-tab">...</div>
                <div class="tab-pane fade" id="my-payment" role="tabpanel" aria-labelledby="payment-tab">...</div>
                <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="password-tab">...</div>

            </div>
        </div>
    </div>
    </div>





@endsection