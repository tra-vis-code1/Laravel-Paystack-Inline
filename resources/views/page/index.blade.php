<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Paystack Payments</title>
</head>
<body>
    <div class="container">
        <form id="paymentForm">
          @csrf
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email-address" class="form-control" required />
            </div>
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="tel" id="amount" class="form-control" required />
            </div>
            <div class="form-group">
              <label for="first-name">First Name</label>
              <input type="text" id="first-name" class="form-control" />
            </div>
            <div class="form-group">
              <label for="last-name">Last Name</label>
              <input type="text" id="last-name" class="form-control" />
            </div>
            <div class="form-submit">
              <button type="submit" class="btn btn-primary" onclick="payWithPaystack()"> Pay </button>
            </div>
        </form>
         
    </div>

    <script src="https://js.paystack.co/v1/inline.js"></script> 
    <script>
        var paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener('submit', payWithPaystack, false);
        function payWithPaystack(e) {
            e.preventDefault();
            var handler = PaystackPop.setup({
                key: 'pk_test_b3e254392ba5d8f8382e46733a0d438990b10969', // Replace with your public key
                email: document.getElementById('email-address').value,
                amount: document.getElementById('amount').value * 100, // the amount value is multiplied by 100 to convert to the lowest currency unit
                currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
                ref: 'TX_'+ Math.floor(Math.random() * 100000), // Replace with a reference you generated
                callback: function(response) {

                    //making AJAX request
                    var reference = response.reference;
                    var _token = $("input[name='_token']").val();
                    
                    $.ajax({
                        url: "{{URL::to('verify-transaction')}}",
                        method: 'POST',
                        data: {
                            reference,
                            _token
                        },
                        success: function (response) {
                          // the transaction status is in response.data.status
                          console.log(response);
                        }
                      }); 
                },
                onClose: function() {
                    alert('Transaction was not completed, window closed.');
                },
            });
            handler.openIframe();
        }
    </script>
</body>
</html>