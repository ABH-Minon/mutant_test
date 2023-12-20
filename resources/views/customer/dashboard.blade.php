<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <style>
        .product-table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .product-table th, .product-table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .product-list {
            display: flex;
            justify-content: space-around;
        }
    </style>
</head>
<body>
    <a href="./personalInfo"><button>View personal information</button></a></li>
    <a href="./newPassword"><button>Change password</button></a></li>
    <a href="cart"><button>Cart</button></a></li>
    <a href="../"><button>Logout</button></a></li>
    <form id="buyForm" action="{{ url('/customer/cartProduct') }}" method="post">
        @csrf  
        <table class="product-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->productName }}</td>
                        <td>${{ $product->productPrice }}</td>
                        <td>
                            <button class="buy-button" onclick="buyProduct('{{ $product->id }}', '{{ $product->productName }}', '{{ $product->productPrice }}')">Add</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <input type="hidden" name="productId" id="product-id">
        <input type="hidden" name="productName" id="product-name">
        <input type="hidden" name="productPrice" id="product-price">
        <input type="hidden" name="quantity" id="quantity-no">
    </form>
    
    <script>
        function buyProduct(productId, productName, productPrice) {
            var buyButtons = document.querySelectorAll('.buy-button');

            // Disable all buy buttons
            buyButtons.forEach(function(button) {
                button.disabled = true;
            });

            var quantity = prompt('Enter quantity for ' + productName + ':', '1');

            if (quantity === null) {
                // Re-enable buttons if the user cancels
                buyButtons.forEach(function(button) {
                    button.disabled = false;
                });
                return;
            }

            quantity = parseInt(quantity, 10);
            if (isNaN(quantity) || quantity <= 0) {
                alert('Please enter a valid positive quantity.');

                // Re-enable buttons if the quantity is invalid
                buyButtons.forEach(function(button) {
                    button.disabled = false;
                });
                return;
            }

            document.getElementById('product-id').value = productId;
            document.getElementById('product-name').value = productName;
            document.getElementById('product-price').value = productPrice;
            document.getElementById('quantity-no').value = quantity;

            var form = document.getElementById('buyForm');
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log('XHR Status:', xhr.status);
                    console.log('Response:', xhr.responseText);

                    if (xhr.status === 200) {
                        alert('Added to cart!');
                        document.getElementById('quantity-no').value = '';
                        window.location.href = '/customer/dashboard';
                    } else {
                        alert('Error: ' + xhr.responseText);
                    }

                    // Re-enable buttons after processing the request
                    buyButtons.forEach(function(button) {
                        button.disabled = false;
                    });
                }
            };
            xhr.send(formData);
        }
    </script>
</body>
</html>
