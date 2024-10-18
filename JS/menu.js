const addContainer = document.querySelectorAll('.add-container');
const addToCartBtn = document.querySelectorAll(".add-to-cart-btn"); //Add to cart button
const incrementBtns = document.querySelectorAll(".increment-btn");
const decrementBtns = document.querySelectorAll(".decrement-btn")


const searchBar = document.getElementById("filter-menu");
const filterInput = document.getElementById("filter");
const filterForm = document.querySelector(".filter-form")
const form = document.querySelector(".add-to-cart-form");


const allMenu = document.querySelectorAll(".menu-container");
const allProductName = document.querySelectorAll(".product-name");
const filterValue = document.querySelector("#filter");

function filterJS() {
    console.log("INPUT:", filterValue.value)
    let allItemStatus = [];

    for (let i = 0; i < allProductName.length; i++) {
        if (allProductName[i].textContent.toLowerCase().includes(filterValue.value) && filterValue.value !== "") {
            console.log("Found");
            document.getElementById('no-item').style.display = "none";
            console.log(allProductName[i].textContent);
            allMenu[i].style.display = "flex";
            allItemStatus.push("true");
            
        } else if (filterValue.value.trim() == "") {
            document.getElementById('no-item').style.display = "none";
            allMenu[i].style.display = "flex";
            allItemStatus.push("true");
            console.log(allItemStatus);
            console.log("ALL");
        } else {
            console.log("HIDE THIS: ", allProductName[i].textContent)
            allMenu[i].style.display = 'none';
            allItemStatus.push("false");

        }
    }

    if (!allItemStatus.includes("true")) {
        document.getElementById('no-item').style.display = "inline";
        document.getElementById('no-item').textContent = "No item found";
    }
}

filterInput.addEventListener("input", filterJS);

document.body.addEventListener("click", (e) => {

    if (e.target.textContent == "Add to cart" && e.target.classList.contains("add-to-cart-btn")) { //Add to cart
        console.log("test");
        const productID = e.target.dataset.productid;

        e.target.parentElement.innerHTML = 
        `
            <i class="fa-solid fa-minus decrement-btn" data-productid=${productID}></i>
            <button type="button" class="add-to-cart-btn" data-productid=${productID}>1</button>
            <i class="fa-regular fa-plus increment-btn" data-productid=${productID}></i>
        `
        
        $.ajax({
            type: 'POST',
            url: 'menu.php',
            data:{'add-to-cart': productID},
        })

    } else if (e.target.classList.contains("increment-btn")) { //Increment
        console.log("incremented");

        let productID = e.target.dataset.productid;

            Number(e.target.previousElementSibling.textContent++)
       
            $.ajax({
            type: 'POST',
            url: 'menu.php',
            data:{'add-to-cart': productID},
        })

    

    } else if (e.target.classList.contains("decrement-btn")) { //Decrement
        console.log("decrement")
        const productID = e.target.dataset.productid;
        let quantity = Number(e.target.nextElementSibling.textContent);

        if (quantity > 1) {
            console.log("reduced 1 to db");
            e.target.nextElementSibling.textContent = --quantity;
        } else if (quantity == 1) {
            e.target.parentElement.innerHTML = `
                <button type="button" class="add-to-cart-btn" data-productid=${productID}>Add to cart</button>
            `
        }

        //Reduces/Removes cart in DB
            $.ajax({
            type: 'POST',
            url: 'menu.php',
            data:{'reduce-cart': productID},
        })

    }
});