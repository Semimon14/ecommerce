document.addEventListener("DOMContentLoaded", function() {
    const currentUrl = window.location.href;
    const menuItems = document.querySelectorAll(".navbar-nav .nav-link");

    menuItems.forEach(item => {
        if (currentUrl.includes(item.getAttribute("href"))) {
            item.classList.add("active");
        }
    });

    const addToCartButtons = document.querySelectorAll(".add-to-cart");
    addToCartButtons.forEach(button => {
        button.addEventListener("click", function() {
            const productId = this.getAttribute("data-product-id");
            const quantity = document.getElementById("quantity") ? document.getElementById("quantity").value : 1;
            const coverSpin = document.getElementById("cover-spin");
            coverSpin.style.display = "block";
            this.disabled = true;

            fetch("cart-add.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(data.message);
                    // Optionally, update the cart count in the navbar
                    const cartLink = document.querySelector(".nav-link[href='cart.php']");
                    const cartCount = parseInt(cartLink.textContent.match(/\d+/)[0]) + parseInt(quantity);
                    cartLink.textContent = `ตะกร้า (${cartCount})`;
                }
                coverSpin.style.display = "none";
                this.disabled = false;
            });
        });
    });

    const deleteButtons = document.querySelectorAll(".delete-item");
    deleteButtons.forEach(button => {
        button.addEventListener("click", function() {
            const productId = this.getAttribute("data-product-id");
            if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้ออกจากตะกร้า?")) {
                fetch("cart-remove.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `product_id=${productId}`
                })
                .then(() => {
                    window.location.reload();
                });
            }
        });
    });
});
