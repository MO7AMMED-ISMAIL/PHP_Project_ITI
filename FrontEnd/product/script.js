// Product Selection and Order during click
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.card');
    const selectedProductName = document.getElementById('selectedProductName');
    const selectedProductPrice = document.getElementById('selectedProductPrice');
    const orderButton = document.getElementById('orderButton');

    let selectedProduct = null;

    productCards.forEach(function(card) {
        card.addEventListener('click', function() {
            const productName = card.querySelector('.card-title').innerText;
            const productPrice = parseFloat(card.querySelector('.card-text').innerText.split(': ')[1]);
            const productId = card.querySelector('.product-id').value; // Retrieve product ID

            selectedProductName.innerText = productName;
            selectedProductPrice.innerText = '$' + productPrice.toFixed(2);
            orderButton.disabled = false;

            selectedProduct = {
                id: productId,
                name: productName,
                price: productPrice
            };
        });
    });
});




//selected product at form

    document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.card');
    const orderForm = document.getElementById('orderForm');
    const selectedProductsContainer = document.getElementById('selectedProducts');
    let selectedProductsList = []; 

    productCards.forEach(function(card) {
        card.addEventListener('click', function() {
            const productName = card.querySelector('.card-title').innerText;
            const productPrice = parseFloat(card.querySelector('.card-text').innerText.split(': ')[1]);

            // if product is already selected
            const existingProductIndex = selectedProductsList.findIndex(product => product.name === productName);
            if (existingProductIndex !== -1) {

                selectedProductsList[existingProductIndex].quantity++;
            } else {
                // Add new one to list
                selectedProductsList.push({ name: productName, price: productPrice, quantity: 1 });
            }

            
            updateOrderForm();
        });
    });



//update selected product at form
    function updateOrderForm() {
    let totalPrice = 0;
    let selectedProductsHTML = '';

    selectedProductsList.forEach((product, index) => {
        const totalProductPrice = product.price * product.quantity;
        totalPrice += totalProductPrice;
        const quantitySign = product.quantity > 0 ? '+' : '-';
        //ID to each product
        const productId = `selectedProduct_${index}`;
        selectedProductsHTML += `
            <div id="${productId}" class="selected-product">
                ${product.name} - Quantity: ${quantitySign}${Math.abs(product.quantity)}
                <button class=" btn btn-primary" style="border-radius:20%;" onclick="changeQuantity('${product.name}', 1)">+</button>
                <button id="decr" class=" btn btn-danger" style="border-radius:20%;" onclick="changeQuantity('${product.name}', -1)">-</button>
                - $${totalProductPrice.toFixed(2)}
                <button class="btn btn-danger close-icon" onclick="removeProduct('${productId}')">×</button>
            </div>`;
    });

    selectedProductsContainer.innerHTML = selectedProductsHTML;
    document.getElementById('totalPrice').value = totalPrice.toFixed(2);
    document.getElementById('orderButton').disabled = false; 
}





    //quantity change
    window.changeQuantity = function(name, change) {
        const productIndex = selectedProductsList.findIndex(product => product.name === name);
        if (productIndex !== -1) {
            selectedProductsList[productIndex].quantity += change;
            if (selectedProductsList[productIndex].quantity <= 0) {
            
                selectedProductsList.splice(productIndex, 1);
            }
            updateOrderForm();
        }
    };



    //form submission
    orderForm.addEventListener('submit', function(event) {
        event.preventDefault();
        selectedProductsList = [];
        updateOrderForm();
    });



    // Clear all products
    document.getElementById('removeAllProducts').addEventListener('click', function() {
        selectedProductsList = [];
        updateOrderForm();
    });


    

    // Remove product
    window.removeProduct = function(productId) {
        const indexToRemove = parseInt(productId.split('_')[1]);
        selectedProductsList.splice(indexToRemove, 1);
        updateOrderForm();
    };
});