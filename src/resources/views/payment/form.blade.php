@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <script src="https://js.stripe.com/v3/"></script>
@endsection


@section('content')
    <div class="payment-group">
        <form action="{{ route('payment.process') }}" method="post" id="payment-form" class="payment_form">
            @csrf
            <div class="form-row">
                <label for="card-number-element" class="label_card_content">
                    カード番号
                </label>
                <div id="card-number-element" class="card-content">
                    <!-- Stripeのカード番号要素がここに挿入されます -->
                </div>
            </div>
            <div class="form-row">
                <label for="card-expiry-element" class="label_card_content">
                    有効期限
                </label>
                <div id="card-expiry-element" class="card-content">
                    <!-- Stripeの有効期限要素がここに挿入されます -->
                </div>
            </div>
            <div class="form-row">
                <label for="card-cvc-element" class="label_card_content">
                    CVC
                </label>
                <div id="card-cvc-element" class="card-content">
                    <!-- StripeのCVC要素がここに挿入されます -->
                </div>
            </div>
            <div id="card-errors" role="alert"></div>
                <button type="submit" class="payment-button">支払う</button>
        </form>
    </div>
    <script>
        var stripe = Stripe('pk_test_51PmGJ2HFRbtTxjfgZZo9KG9kYXwmjCAAUn1cJRqV7apN8uhrU1RuOXlNGadgH0n16kTGJKPDtsKERO6N5l3QXSnm00FRhEumuA');
        var elements = stripe.elements();
        var style = {
            base: {
                color: "#000000",
                fontSize: "1rem",
                lineHeight: "30px",
                textAlign: "center",
                '::placeholder': {
                color: '#808080'
            }
            }
        };

        var cardNumber = elements.create('cardNumber', {style: style});
        cardNumber.mount('#card-number-element');

        var cardExpiry = elements.create('cardExpiry', {style: style});
        cardExpiry.mount('#card-expiry-element');

        var cardCvc = elements.create('cardCvc', {style: style});
        cardCvc.mount('#card-cvc-element');

        function setErrorMessage(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        }

        cardNumber.addEventListener('change', setErrorMessage);
        cardExpiry.addEventListener('change', setErrorMessage);
        cardCvc.addEventListener('change', setErrorMessage);

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(cardNumber).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', result.token.id);
                    form.appendChild(hiddenInput);

                    form.submit();
                }
            });
        });
    </script>
@endsection