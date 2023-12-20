<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div>
        <h2>Your Cart</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totalOrder as $order)
                    <tr>
                        <td>{{ $order['productName'] }}</td>
                        <td>${{ $order['productPrice'] }}</td>
                        <td>{{ $order['quantity'] }}</td>
                        <td>${{ $order['total'] }}</td>
                        <td>
                            <button onclick="removeOrder('{{ $order['id'] }}')">Remove</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button onclick="openCheckoutModal()">Checkout</button>
    </div>

    <div id="checkoutModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCheckoutModal()">&times;</span>
            <h2>Checkout</h2>
            <p>Subtotal: $<span id="subtotal"></span></p>
            <button onclick="checkout()">Complete Purchase</button>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        function removeOrder(orderID) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/removeOrder/' + orderID, true);
            xhr.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log('XHR Status:', xhr.status);
                    console.log('Response:', xhr.responseText);
                    if (xhr.status === 200) {
                        alert('Order removed successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + xhr.responseText);
                    }
                }
            };
            xhr.send();
        }

        function openCheckoutModal() {
            var subtotal = calculateSubtotal();
            document.getElementById('subtotal').innerText = subtotal;
            document.getElementById('checkoutModal').style.display = 'block';
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').style.display = 'none';
        }

        function calculateSubtotal() {
            var subtotal = 0;
            @foreach($totalOrder as $order)
                subtotal += {{ $order['total'] }};
            @endforeach
            return subtotal;
        }

        function checkout() {
            var subtotal = calculateSubtotal();
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/create-checkout-session', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log('XHR Status:', xhr.status);
                    console.log('Response:', xhr.responseText);
                    if (xhr.status === 200) {
                        // Parse the response to get the Checkout Session URL
                        var sessionUrl = JSON.parse(xhr.responseText).url;

                        // Redirect to the Checkout Session URL
                        window.location.href = sessionUrl;
                    } else {
                        alert('Error: ' + xhr.responseText);
                    }
                }
            };

            // Send an empty JSON payload since you are not sending any data
            xhr.send(JSON.stringify({ subtotal: subtotal }));
        }

        var stripe = Stripe('pk_test_51MntetCvBL6iYfWCR2SPZQgfntB6xsxko19jFbbJMM4v2vtni8aFeBSAGHca4kRUJiM0AWxaC3nnFGHZkpJLvhG500TuS3buoh');

    </script>
</body>
</html>
