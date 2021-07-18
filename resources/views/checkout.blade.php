<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Stripe Sample</title>
    <meta name="description" content="A demo of Stripe Payment Intents" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo asset('css/style.css') ?>" type="text/css">

    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <div class="sr-root">
        <div class="sr-main">
            <header class="sr-header">
                <div class="sr-header__logo"></div>
            </header>
            <div class="sr-payment-summary payment-view">
                <h1 class="order-amount">€ {{$plan->price}}</h1>
                <h4>Loveats Subscription</h4>
            </div>
            <div class="sr-payment-form payment-view">
                <div class="sr-form-row">
                    <label for="card-element">
                        Payment details
                    </label>
                    <div class="sr-combo-inputs">
                        <div class="sr-combo-inputs-row">
                            <input type="text" id="name" placeholder="Name" autocomplete="cardholder"
                                class="sr-input" />
                        </div>
                        <div class="sr-combo-inputs-row">
                            <div class="sr-input sr-card-element" id="card-element"></div>
                        </div>
                    </div>
                    <div class="sr-field-error" id="card-errors" role="alert"></div>
                    <div class="sr-form-row">
                        <label class="sr-checkbox-label"><input type="checkbox" id="save-card"><span
                                class="sr-checkbox-check"></span> Save card for future payments</label>
                    </div>
                </div>
                <button id="submit">
                    <div class="spinner hidden" id="spinner"></div><span id="button-text">Pay</span>
                </button>
                <div class="sr-legal-text">
                    Your card will be charge € {{$plan->price}}<span id="save-card-text"> and your card details will be saved to
                        your account</span>.
                </div>
            </div>
            {{-- <div class="sr-payment-summary hidden completed-view">
                <h1>Your payment <span class="status"></span></h1>
                <h4>
                    View PaymentIntent response:</a>
                </h4>
            </div>
            <div class="sr-section hidden completed-view">
                <div class="sr-callout">
                    <pre>

            </pre>
                </div>
                <button onclick="window.location.href = '/';">Restart demo</button>
            </div> --}}
        </div>
        <div class="sr-content">
            <div class="pasha-image-stack">
                <img src="https://picsum.photos/280/320?random=1" width="140" height="160" />
                <img src="https://picsum.photos/280/320?random=2" width="140" height="160" />
                <img src="https://picsum.photos/280/320?random=3" width="140" height="160" />
                <img src="https://picsum.photos/280/320?random=4" width="140" height="160" />
            </div>
        </div>
    </div>
</body>
<script>
    // A reference to Stripe.js
    var stripe;
    id = {!! json_encode($id) !!};


    var orderData = {
        items: [{
            id: "photo-subscription"
        }],
        currency: "usd",
        user_id: id,
        price:{!! json_encode($plan->price) !!},
    };

    fetch("/api/get-all-subscription-plans")
        .then(function(result) {
            console.log(result.body);
            return result.json();
        })
        .then(function(data) {
            return setupElements(data);
        })
        .then(function({
            stripe,
            card,
            clientSecret
        }) {
            document.querySelector("#submit").addEventListener("click", function(evt) {
                evt.preventDefault();
                pay(stripe, card, clientSecret);
            });
        });

    var setupElements = function(data) {
        stripe = Stripe(
            'pk_test_51ISmUBHxiL0NyAbFEVjryq52Z9kzhSVCzWlz4dTKFFk8m36jvkHmcyhbFDFzJ1tjV7BtOGtcU56sG9uhosU3mz3e00MAu7hMUv'
            );
        /* ------- Set up Stripe Elements to use in checkout form ------- */
        var elements = stripe.elements();
        var style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        var card = elements.create("card", {
            style: style
        });
        card.mount("#card-element");

        return {
            stripe,
            card,
            clientSecret: data.clientSecret
        };
    };

    var handleAction = function(clientSecret) {
        // Show the authentication modal if the PaymentIntent has a status of "requires_action"
        stripe.handleCardAction(clientSecret).then(function(data) {
            if (data.error) {
                showError("Your card was not authenticated, please try again");
            } else if (data.paymentIntent.status === "requires_confirmation") {
                // Card was properly authenticated, we can attempt to confirm the payment again with the same PaymentIntent
                fetch("/pay", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            paymentIntentId: data.paymentIntent.id
                        })
                    })
                    .then(function(result) {
                        return result.json();
                    })
                    .then(function(json) {
                        if (json.error) {
                            showError(json.error);
                        } else {
                            orderComplete(clientSecret);
                        }
                    });
            }
        });
    };

    /*
     * Collect card details and pay for the order 
     */
    var pay = function(stripe, card) {
        var cardholderName = document.querySelector("#name").value;
        var data = {
            billing_details: {}
        };

        if (cardholderName) {
            data["billing_details"]["name"] = cardholderName;
        }

        changeLoadingState(true);

        // Collect card details
        stripe
            .createPaymentMethod("card", card, data)
            .then(function(result) {
                if (result.error) {
                    showError(result.error.message);
                } else {
                    //   alert(result.paymentMethod.id);
                    orderData.paymentMethodId = result.paymentMethod.id;
                    orderData.isSavingCard = true;
                    orderData.paymentMethod = result.paymentMethod;

                    return fetch("/api/storeSubscription", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(orderData)
                    });
                }
            })
            .then(function(result) {
                return result.json();
            })
            .then(function(paymentData) {
                if (paymentData.requiresAction) {
                    // Request authentication
                    handleAction(paymentData.clientSecret);
                } else if (paymentData.error) {
                    showError(paymentData.error);
                } else {
                    orderComplete(paymentData.clientSecret);
                }
            });
    };

    /* ------- Post-payment helpers ------- */

    /* Shows a success / error message when the payment is complete */
    var orderComplete = function(clientSecret) {
        stripe.retrievePaymentIntent(clientSecret).then(function(result) {
            var paymentIntent = result.paymentIntent;
            var paymentIntentJson = JSON.stringify(paymentIntent, null, 2);
            document.querySelectorAll(".payment-view").forEach(function(view) {
                view.classList.add("hidden");
            });
            document.querySelectorAll(".completed-view").forEach(function(view) {
                view.classList.remove("hidden");
            });
            document.querySelector(".status").textContent =
                paymentIntent.status === "succeeded" ? "succeeded" : "failed";
            document.querySelector("pre").textContent = paymentIntentJson;
        });
    };

    var showError = function(errorMsgText) {
        changeLoadingState(false);
        var errorMsg = document.querySelector(".sr-field-error");
        errorMsg.textContent = errorMsgText;
        setTimeout(function() {
            errorMsg.textContent = "";
        }, 4000);
    };

    // Show a spinner on payment submission
    var changeLoadingState = function(isLoading) {
        if (isLoading) {
            document.querySelector("button").disabled = true;
            document.querySelector("#spinner").classList.remove("hidden");
            document.querySelector("#button-text").classList.add("hidden");
        } else {
            document.querySelector("button").disabled = false;
            document.querySelector("#spinner").classList.add("hidden");
            document.querySelector("#button-text").classList.remove("hidden");
        }
    };
</script>

</html>
