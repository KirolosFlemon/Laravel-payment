@extends('layouts.app')

@section('title', 'Online Payment')

@section('content')
@include('layouts.navigation')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Online Payment</h4>
                </div>

                <div class="card-body">
                    <!-- <form method="post" id="paymentForm"> -->
                    <form method="post" action="{{ route('transaction') }}">
                        @csrf

                        <!-- Product Details Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">Product Details</h5>
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" name="product_name" id="product_name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="qty" id="quantity" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" name="price" id="price" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="code" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" name="address" id="address" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" name="city" id="city" class="form-control" required>
                            </div>
                        </div>

                        <!-- Payment Details Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">Payment Details</h5>

                            <!-- Card Number -->
                            <div class="form-group">
                                <label for="card_number">Card Number</label>
                                <input type="number" name="card_number" id="card_number" class="form-control" required>
                            </div>

                            <!-- Expiration Date and CVV -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="expiration_date">Expiration Date</label>
                                    <input type="date" name="expiration_date" id="expiration_date" class="form-control" placeholder="MM/YYYY" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="number">CVV</label>
                                    <input type="number" name="cvv" id="cvv" class="form-control" required>
                                </div>
                            </div>



                            <button type="submit" id="submit" class="btn btn-primary">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Include jQuery from CDN -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<script>
    const csrfToken = $('meta[name="access-token"]').attr('content');
    $(document).ready(function() {
        $('#_submit').click(function() {
            // Serialize the form data
            var formData = $('#paymentForm').serialize();
            // Set up headers with CSRF token and access token
            var headers = {
                'X-CSRF-TOKEN': csrfToken,
            };

            // Send the data using Ajax
            $.ajax({
                url: '/transaction', // Replace with your Laravel route
                type: 'POST',
                data: formData,
                headers: headers,
                success: function(response) {
                    // Handle the success response
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Handle the error response
                    console.error('Ajax Error:', error);
                }
            });
        });
    });
</script>
@endsection