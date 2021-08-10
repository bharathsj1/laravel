<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Stripe Sample</title>
    <meta name="description" content="A demo of Stripe Payment Intents" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    {{-- <link rel="stylesheet" href="<?php echo asset('css/style.css') ?>" type="text/css"> --}}

    <script src="https://js.stripe.com/v3/"></script>

    <style>
            /* Variables */
:root {
  --gray-offset: rgba(0, 0, 0, 0.03);
  --gray-border: rgba(0, 0, 0, 0.15);
  --gray-light: rgba(0, 0, 0, 0.4);
  --gray-mid: rgba(0, 0, 0, 0.7);
  --gray-dark: rgba(0, 0, 0, 0.9);
  --body-color: var(--gray-mid);
  --headline-color: var(--gray-dark);
  --accent-color: #0066f0;
  --body-font-family: -apple-system, BlinkMacSystemFont, sans-serif;
  --radius: 6px;
  --logo-image: url("https://storage.googleapis.com/stripe-sample-images/KAVHOLM.svg");
  --form-width: 343px;
}

/* Base */
* {
  box-sizing: border-box;
}
body {
  font-family: var(--body-font-family);
  font-size: 16px;
  color: var(--body-color);
  -webkit-font-smoothing: antialiased;
}
h1,
h2,
h3,
h4,
h5,
h6 {
  color: var(--body-color);
  margin-top: 2px;
  margin-bottom: 4px;
}
h1 {
  font-size: 27px;
  color: var(--headline-color);
}
h4 {
  font-weight: 500;
  font-size: 14px;
  color: var(--gray-light);
}

/* Layout */
.sr-root {
  display: flex;
  flex-direction: row;
  width: 100%;
  max-width: 980px;
  padding: 48px;
  align-content: center;
  justify-content: center;
  height: auto;
  min-height: 100vh;
  margin: 0 auto;
}
.sr-header {
  margin-bottom: 32px;
}
.sr-payment-summary {
  margin-bottom: 20px;
}
.sr-main,
.sr-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
  align-self: center;
}
.sr-main {
  width: var(--form-width);
}
.sr-content {
  padding-left: 48px;
}
.sr-header__logo {
  background-image: var(--logo-image);
  height: 24px;
  background-size: contain;
  background-repeat: no-repeat;
  width: 100%;
}
.sr-legal-text {
  color: var(--gray-light);
  text-align: center;
  font-size: 13px;
  line-height: 17px;
  margin-top: 12px;
}
.sr-field-error {
  color: var(--accent-color);
  text-align: left;
  font-size: 13px;
  line-height: 17px;
  margin-top: 12px;
}

/* Form */
.sr-form-row {
  margin: 16px 0;
}
label {
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 8px;
  display: inline-block;
}

/* Inputs */
.sr-input,
.sr-select,
input[type="text"] {
  border: 1px solid var(--gray-border);
  border-radius: var(--radius);
  padding: 5px 12px;
  height: 44px;
  width: 100%;
  transition: box-shadow 0.2s ease;
  background: white;
  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
}
.sr-input:focus,
input[type="text"]:focus,
button:focus,
.focused {
  box-shadow: 0 0 0 1px rgba(50, 151, 211, 0.3), 0 1px 1px 0 rgba(0, 0, 0, 0.07),
    0 0 0 4px rgba(50, 151, 211, 0.3);
  outline: none;
  z-index: 9;
}
.sr-input::placeholder,
input[type="text"]::placeholder {
  color: var(--gray-light);
}

/* Checkbox */
.sr-checkbox-label {
  position: relative;
  cursor: pointer;
}

.sr-checkbox-label input {
  opacity: 0;
  margin-right: 6px;
}

.sr-checkbox-label .sr-checkbox-check {
  position: absolute;
  left: 0;
  height: 16px;
  width: 16px;
  background-color: white;
  border: 1px solid var(--gray-border);
  border-radius: 4px;
  transition: all 0.2s ease;
}

.sr-checkbox-label input:focus ~ .sr-checkbox-check {
  box-shadow: 0 0 0 1px rgba(50, 151, 211, 0.3), 0 1px 1px 0 rgba(0, 0, 0, 0.07),
    0 0 0 4px rgba(50, 151, 211, 0.3);
  outline: none;
}

.sr-checkbox-label input:checked ~ .sr-checkbox-check {
  background-color: var(--accent-color);
  background-image: url("https://storage.googleapis.com/stripe-sample-images/icon-checkmark.svg");
  background-repeat: no-repeat;
  background-size: 16px;
  background-position: -1px -1px;
}

/* Select */
.sr-select {
  display: block;
  height: 44px;
  margin: 0;
  background-image: url("https://storage.googleapis.com/stripe-sample-images/icon-chevron-down.svg");
  background-repeat: no-repeat, repeat;
  background-position: right 12px top 50%, 0 0;
  background-size: 0.65em auto, 100%;
}
.sr-select:after {
}
.sr-select::-ms-expand {
  display: none;
}
.sr-select:hover {
  cursor: pointer;
}
.sr-select:focus {
  box-shadow: 0 0 0 1px rgba(50, 151, 211, 0.3), 0 1px 1px 0 rgba(0, 0, 0, 0.07),
    0 0 0 4px rgba(50, 151, 211, 0.3);
  outline: none;
}
.sr-select option {
  font-weight: 400;
}
.sr-select:invalid {
  color: var(--gray-light);
  background-opacity: 0.4;
}

/* Combo inputs */
.sr-combo-inputs {
  display: flex;
  flex-direction: column;
}
.sr-combo-inputs input,
.sr-combo-inputs .sr-select {
  border-radius: 0;
  border-bottom: 0;
}
.sr-combo-inputs > input:first-child,
.sr-combo-inputs > .sr-select:first-child {
  border-radius: var(--radius) var(--radius) 0 0;
}
.sr-combo-inputs > input:last-child,
.sr-combo-inputs > .sr-select:last-child {
  border-radius: 0 0 var(--radius) var(--radius);
  border-bottom: 1px solid var(--gray-border);
}
.sr-combo-inputs > .sr-combo-inputs-row:last-child input:first-child {
  border-radius: 0 0 0 var(--radius);
  border-bottom: 1px solid var(--gray-border);
}
.sr-combo-inputs > .sr-combo-inputs-row:last-child input:last-child {
  border-radius: 0 0 var(--radius) 0;
  border-bottom: 1px solid var(--gray-border);
}
.sr-combo-inputs > .sr-combo-inputs-row:first-child input:first-child {
  border-radius: var(--radius) 0 0 0;
}
.sr-combo-inputs > .sr-combo-inputs-row:first-child input:last-child {
  border-radius: 0 var(--radius) 0 0;
}
.sr-combo-inputs > .sr-combo-inputs-row:first-child input:only-child {
  border-radius: var(--radius) var(--radius) 0 0;
}
.sr-combo-inputs-row {
  width: 100%;
  display: flex;
}

.sr-combo-inputs-row > input {
  width: 100%;
  border-radius: 0;
}

.sr-combo-inputs-row > input:first-child:not(:only-child) {
  border-right: 0;
}

.sr-combo-inputs-row:not(:first-of-type) .sr-input {
  border-radius: 0 0 var(--radius) var(--radius);
}

/* Buttons and links */
button {
  background: var(--accent-color);
  border-radius: var(--radius);
  color: white;
  border: 0;
  padding: 12px 16px;
  margin-top: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: block;
}
button:hover {
  filter: contrast(115%);
}
button:active {
  transform: translateY(0px) scale(0.98);
  filter: brightness(0.9);
}
button:disabled {
  opacity: 0.5;
  cursor: none;
}

.sr-payment-form button,
.fullwidth {
  width: 100%;
}

a {
  color: var(--accent-color);
  text-decoration: none;
  transition: all 0.2s ease;
}

a:hover {
  filter: brightness(0.8);
}

a:active {
  filter: brightness(0.5);
}

/* Code block */
.sr-callout {
  background: var(--gray-offset);
  padding: 12px;
  border-radius: var(--radius);
  max-height: 200px;
  overflow: auto;
}
code,
pre {
  font-family: "SF Mono", "IBM Plex Mono", "Menlo", monospace;
  font-size: 12px;
}

/* Stripe Element placeholder */
.sr-card-element {
  padding-top: 12px;
}

/* Responsiveness */
@media (max-width: 720px) {
  .sr-root {
    flex-direction: column;
    justify-content: flex-start;
    padding: 48px 20px;
    min-width: 320px;
  }

  .sr-header__logo {
    background-position: center;
  }

  .sr-payment-summary {
    text-align: center;
  }

  .sr-content {
    display: none;
  }

  .sr-main {
    width: 100%;
  }
}

/* Pasha styles – Brand-overrides, can split these out */
:root {
  --accent-color: #ed5f74;
  --headline-color: var(--accent-color);
  --logo-image: url("https://storage.googleapis.com/stripe-sample-images/logo-pasha.svg");
}

.pasha-image-stack {
  display: grid;
  grid-gap: 12px;
  grid-template-columns: auto auto;
}

.pasha-image-stack img {
  border-radius: var(--radius);
  background-color: var(--gray-border);
  box-shadow: 0 7px 14px 0 rgba(50, 50, 93, 0.1),
    0 3px 6px 0 rgba(0, 0, 0, 0.07);
  transition: all 0.8s ease;
  opacity: 0;
}

.pasha-image-stack img:nth-child(1) {
  transform: translate(12px, -12px);
  opacity: 1;
}
.pasha-image-stack img:nth-child(2) {
  transform: translate(-24px, 16px);
  opacity: 1;
}
.pasha-image-stack img:nth-child(3) {
  transform: translate(68px, -100px);
  opacity: 1;
}

/* todo: spinner/processing state, errors, animations */

.spinner,
.spinner:before,
.spinner:after {
  border-radius: 50%;
}
.spinner {
  color: #ffffff;
  font-size: 22px;
  text-indent: -99999px;
  margin: 0px auto;
  position: relative;
  width: 20px;
  height: 20px;
  box-shadow: inset 0 0 0 2px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.spinner:before,
.spinner:after {
  position: absolute;
  content: "";
}
.spinner:before {
  width: 10.4px;
  height: 20.4px;
  background: var(--accent-color);
  border-radius: 20.4px 0 0 20.4px;
  top: -0.2px;
  left: -0.2px;
  -webkit-transform-origin: 10.4px 10.2px;
  transform-origin: 10.4px 10.2px;
  -webkit-animation: loading 2s infinite ease 1.5s;
  animation: loading 2s infinite ease 1.5s;
}
.spinner:after {
  width: 10.4px;
  height: 10.2px;
  background: var(--accent-color);
  border-radius: 0 10.2px 10.2px 0;
  top: -0.1px;
  left: 10.2px;
  -webkit-transform-origin: 0px 10.2px;
  transform-origin: 0px 10.2px;
  -webkit-animation: loading 2s infinite ease;
  animation: loading 2s infinite ease;
}
@-webkit-keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

/* Animated form */

.sr-root {
  animation: 0.4s form-in;
  animation-fill-mode: both;
  animation-timing-function: ease;
}

.sr-payment-form .sr-form-row {
  animation: 0.4s field-in;
  animation-fill-mode: both;
  animation-timing-function: ease;
  transform-origin: 50% 0%;
}

/* need saas for loop :D  */
.sr-payment-form .sr-form-row:nth-child(1) {
  animation-delay: 0;
}
.sr-payment-form .sr-form-row:nth-child(2) {
  animation-delay: 60ms;
}
.sr-payment-form .sr-form-row:nth-child(3) {
  animation-delay: 120ms;
}
.sr-payment-form .sr-form-row:nth-child(4) {
  animation-delay: 180ms;
}
.sr-payment-form .sr-form-row:nth-child(5) {
  animation-delay: 240ms;
}
.sr-payment-form .sr-form-row:nth-child(6) {
  animation-delay: 300ms;
}
.hidden {
  display: none;
}

@keyframes field-in {
  0% {
    opacity: 0;
    transform: translateY(8px) scale(0.95);
  }
  100% {
    opacity: 1;
    transform: translateY(0px) scale(1);
  }
}

@keyframes form-in {
  0% {
    opacity: 0;
    transform: scale(0.98);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

    </style>
</head>

<body>
    <div class="sr-root">
        <div class="sr-main">
            <header class="sr-header">
                <div class="sr-header__logo"></div>
            </header>
            @if(session('status'))
            <div class="alert-danger px-2 py-2 mb-2 text-center">
                {{session('message')}}
            </div>
            @endif
            <div class="sr-payment-summary payment-view">
                <h1 class="order-amount">€ {{$plan->metadata->amount}}</h1>
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
                  
                </div>
                <button id="submit">
                    <div class="spinner hidden" id="spinner"></div><span id="button-text">Pay</span>
                </button>
                <div class="sr-legal-text">
                    Your card will be charge € {{$payment->unit_amount /100}}<span id="save-card-text"> and your card details will be saved to
                        your account</span>.
                        <span> Your User id is {{$user_id}}</span>
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
    id = {!! json_encode($user_id) !!};



    var orderData = {
      
        currency: "gbp",
        user_id: id,
        price:{!! json_encode(($payment->unit_amount)) !!},
        plan_id:{!! json_encode($plan->id) !!},
        price_id:{!!json_encode($payment->id)!!},
        total_receipes:{!!json_encode($totalReceipes)!!},

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
                console.log(result);
             
                
                  
                    orderData.paymentMethodId = result.paymentMethod.id;
                    orderData.isSavingCard = true;
                    orderData.paymentMethod = result.paymentMethod;
                
                    console.log('hello g');

                     fetch("/api/storeReceipeSubscription", {
                        method: "POST",
                        mode: 'cors',
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(orderData)
                    }).then(function(result){
                   // window.location.href = "/checkout?success";
             
                    });
                }
            })
            .then(function(result) {
              // console.log(result.body());
                changeLoadingState(false);
              
              //   if(result.success==true)
              //  window.location.href = "/checkout?success";
                // return result.json();
            });
            // .then(function(paymentData) {
            //     if (paymentData.requiresAction) {
            //         // Request authentication
            //         handleAction(paymentData.clientSecret);
            //     } else if (paymentData.error) {
            //         showError(paymentData.error);
            //     } else {
            //         orderComplete(paymentData.clientSecret);
            //     }
            // });
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