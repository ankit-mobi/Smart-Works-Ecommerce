@extends('web.layout.app')

@section('content')



    <style>
        .payment-option {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
            cursor: pointer;
        }
        .payment-option:hover {
            border-color: #ff9800;
            background-color: #fff9e6;
        }
        .payment-option.selected {
            border-color: #ff9800;
            background-color: #fff3cd;
        }
        .payment-icon {
            font-size: 24px;
            margin-right: 15px;
            color: #6c757d;
        }
        .payment-details {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            display: none;
        }
        .form-check-input:checked {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        .promo-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .btn-apply {
            background-color: #ff9800;
            color: white;
            font-weight: 600;
        }
        .btn-apply:hover {
            background-color: #e68900;
        }
        .section-title {
            color: #495057;
            border-bottom: 2px solid #ff9800;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
<br>
    <div class="container py-4">
        <div class="row">
            <!-- Left Side: Title and Form -->
            <div class="col-md-8">
                <h4 class="section-title">Payment Method</h4>
                
                <!-- Wallet Option -->
                <div class="payment-option" onclick="selectPayment('wallet')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-wallet2 payment-icon"></i>
                            <div>
                                <h5 class="mb-1">Wallet</h5>
                                <p class="mb-0 text-muted">Pay using your wallet balance</p>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="wallet" value="wallet">
                        </div>
                    </div>
                </div>

                <!-- Cash on Delivery Option -->
                <div class="payment-option" onclick="selectPayment('cod')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cash-coin payment-icon"></i>
                            <div>
                                <h5 class="mb-1">Cash on Delivery</h5>
                                <p class="mb-0 text-muted">Pay when you receive your order</p>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="cod">
                        </div>
                    </div>
                </div>

                <!-- Credit/Debit Card/Net Banking Option -->
                <div class="payment-option" onclick="selectPayment('card')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-credit-card payment-icon"></i>
                            <div>
                                <h5 class="mb-1">Credit/Debit Card/Net Banking</h5>
                                <p class="mb-0 text-muted">Secure online payment</p>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="card" value="card">
                        </div>
                    </div>
                    
                    <!-- Dropdown for payment gateway selection -->
                    <div class="payment-details" id="cardDetails">
                        <div class="mb-3">
                            <label for="paymentGateway" class="form-label">Select Payment Gateway</label>
                            <select class="form-select" id="paymentGateway">
                                <option value="">Choose a gateway</option>
                                <option value="razorpay">RazorPay</option>
                                <option value="paypal">PayPal</option>
                                <option value="paystack">Paystack</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Promo Code Section -->
                <div class="promo-section">
                    <h5 class="mb-3">Promo Code</h5>
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <input type="text" class="form-control" id="promoCode" placeholder="Enter promo code">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-apply w-100">Apply</button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Enter your promo code to avail discounts</small>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rs 650</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Rs 50</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>Rs 7</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="text-danger">Rs 707</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button class="btn btn-warning w-100 fw-bold">PAY NOW</button>
                    </div>
                </div>

                <!-- Reward Points Info -->
                <div class="alert alert-light mt-3">
                    <h6>Reward Points</h6>
                    <p class="small mb-1">You will get <strong>30 reward points</strong> with successful checkout of this order.</p>
                    <p class="small mb-0">Add items of <strong>Rs 293</strong> more to get <strong>200 reward points</strong>.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectPayment(method) {
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Check the corresponding radio button
            document.getElementById(method).checked = true;
            
            // Show/hide payment gateway dropdown for card option
            const cardDetails = document.getElementById('cardDetails');
            if (method === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        }

        // Initialize with first option selected
        document.addEventListener('DOMContentLoaded', function() {
            selectPayment('wallet');
        });
    </script>


@endsection