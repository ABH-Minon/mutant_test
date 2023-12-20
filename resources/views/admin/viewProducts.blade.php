<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <a href="dashboard"><button>Back</button></a>
    <div class="products-container">
        <h2>Product Lists</h2>
        <button onclick="toggleAddProduct()">Add Product</button>
        <input type="text" id="searchProduct" placeholder="Search Product">
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->productName }}</td>
                        <td>{{ $product->productPrice }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="addProduct" style="display: none;">
        <h2>Add Product</h2>
        <form action="/add-product" method="post">
            @csrf
            <label for="productName">Product Name:</label>
            <input type="text" name="productName" required>
            <br><br>
            <label for="productPrice">Product Price:</label>
            <input type="text" name="productPrice" required>
            <br><br>
            <button type="submit">Add Product</button>
        </form>
    </div>

    <script>
        function toggleAddProduct() {
            var addProductDiv = document.querySelector('.addProduct');
            addProductDiv.style.display = (addProductDiv.style.display === 'none' || addProductDiv.style.display === '') ? 'block' : 'none';
        }
    </script>
</body>
</html>
