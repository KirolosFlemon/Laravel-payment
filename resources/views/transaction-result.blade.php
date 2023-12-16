@extends('layouts.app')

@section('title', 'Online Payment')

@section('content')
@include('layouts.navigation')
<div class="container mt-5">
        <h1 class="mb-4">Transaction Result</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if($transaction->status == 'success')
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Transaction Successful!</h4>
                        <p>Amount: ${{ $transaction->subtotal }}</p>
                        <hr>
                        <p class="mb-0">Thank you for your payment.</p>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Transaction Failed!</h4>
                        <p>Sorry, there was an error processing your payment.</p>
                        <hr>
                        <p class="mb-0">Please try again later.</p>
                    </div>
                @endif

               
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

@endsection