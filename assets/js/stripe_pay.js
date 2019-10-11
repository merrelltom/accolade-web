var stripe = Stripe('pk_test_Zko2pCiERVKRRHiD69eTsZ2z00MuTeMrFi');
//var stripe = Stripe('pk_live_1BFkrKAfN882zpq3dKttfb0i00iLxzent7');

var elements = stripe.elements();
var cardElement = elements.create('card');
cardElement.mount('#card-element');
var cardholderName = document.getElementById('cardholder-name');
var address1 = document.getElementById('cardholder-address1');
var address2 = document.getElementById('cardholder-address2');
var city = document.getElementById('cardholder-city');
var county = document.getElementById('cardholder-county');
var phone = document.getElementById('cardholder-phone');
var cardholderEmail = document.getElementById('cardholder-email');
var cardButton = document.getElementById('card-button');
var clientSecret = cardButton.dataset.secret;

var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();
  stripe.handleCardPayment(
    clientSecret, cardElement, {
      payment_method_data: {
        billing_details: {name: cardholderName.value}
      },
      receipt_email: cardholderEmail.value,
      shipping: {
          name: cardholderName.value,
          address: {
              line1: address1.value,
              line2: address2.value,
              city: city.value,
              county: county.value
          },
          phone: phone.value
      }
    }
  ).then(function(result) {
    if (result.error) {
        console.log(result);
        $("[role=alert]").html("<h3>" + result.error.message + "</h3>");
      // Display error.message in your UI.
    } else {
        console.log(result);
        var idInput = document.createElement('input');
        idInput.setAttribute('type', 'hidden');
        idInput.setAttribute('name', 'payment_id');
        idInput.setAttribute('value', result.paymentIntent.id);
        form.appendChild(idInput);
        var emailInput = document.createElement('input');
        emailInput.setAttribute('type', 'hidden');
        emailInput.setAttribute('name', 'email');
        emailInput.setAttribute('value', cardholderName.value);
        form.appendChild(emailInput);
        form.submit();
    }
  });
});