/*  { */


function addToCart(id){ 
    $.ajax({
        type: 'POST',
        url: 'menu.php',
        data:{'add-to-cart': id},
        success : function (data) {
        }
        
    })
    
    console.log("Added");
}

function test(productid) {
    for (let i = 0; i < addBtns.length; i++) {
        if (addBtns.textContent == "Add to cart" && addBtns[i].dataset.productid == productid) {
            addContainers[i].innerHTML = `
                <button type="button" class="decrement-btn" style="background-color: white;" onclick="decrementCart(${productid})">-</button>
                <button type="button" class="add-btn">1</button>
                <button type="button" class="increment-btn" style="background-color: white;" onclick="addToCart(${productid})">+</button>
            `;
        }
    }
}

incrementBtns.forEach(plus => {
        plus.addEventListener("click", () => {
            for (let i = 0; i < incrementBtns.length; i++) {
                if (plus == incrementBtns[i]) {
                    incrementQty(i);
                } else {
                    console.log("not this");
             }
          }
    })
})

function incrementQty(i) {
    let quantity = incrementBtns[i].previousElementSibling.textContent;
    incrementBtns[i].previousElementSibling.textContent = Number(++quantity);   
}


function decrementCart(id){ 
        $.ajax({
        type: 'POST',
        url: 'menu.php',
        data:{'reduce-cart': id},
        success : function (data) {
        }
    }) 
}

decrementBtns.forEach(btn => {
    btn.addEventListener("click", () => {
            for (let i = 0; i < decrementBtns.length; i++) {
                if (btn == decrementBtns[i]) {
                    console.log("Element:", decrementBtns[i]);
                    console.log("THIS: ", decrementBtns[i].nextElementSibling);
                    console.log(decrementBtns[i].dataset.productid);
                   /*  decrementQty(i, decrementBtns[i].dataset.productid); */

                    let quantity = decrementBtns[i].nextElementSibling.textContent;

                    if(quantity == 1) {
                        console.log("should be deleted");
                        addContainers[i].innerHTML = `<button type="button" class="add-btn" onclick="addToCart(${decrementBtns[i].dataset.productid})" data-productid=${decrementBtns[i].dataset.productid} >Add to cart</button>`;
                        
                    } else {
                        decrementBtns[i].nextElementSibling.textContent = Number(--quantity);
                    }

                    
                } else {
                    console.log("not this: ", decrementBtns[i]);
             }
          }
    })
})

document.body.addEventListener("click", (e) =>
 {
    console.log(e.target.textContent);
    if (e.target.textContent == "Add to cart") {
        e.target.parentElement.innerHTML = `
                <button type="button" class="decrement-btn" style="background-color: white;" onclick="decrementCart(${e.target.dataset.productid})">-</button>
                <button type="button" class="add-btn">1</button>
                <button type="button" class="increment-btn" style="background-color: white;" onclick="addToCart(${e.target.dataset.productid})">+</button>
        `;

        addToCart(e.target.dataset.productid);
    } else if (e.target.textContent == "-" && e.target.nextElementSibling.textContent == "1") {
        e.target.parentElement.innerHTML = `<button type="button" class="add-btn" onclick="addToCart(${e.target.dataset.productid})" data-productid=${e.target.dataset.productid} >Add to cart</button>`;

    } else if (e.target.textContent == "+" && e.target.previousElementSibling.textContent >= 1) {
        let oldQty = e.target.previousElementSibling.textContent
        let newQty = Number(++oldQty);
        e.target.previousElementSibling.textContent = newQty;

    }
})


function decrementQty(i, id) {
    let quantity = decrementBtns[i].nextElementSibling.textContent;

    if(quantity == 1) {
        console.log("should be deleted");
        addContainers[i].innerHTML = `<button type="button" class="add-btn" onclick="addToCart(${id})" data-productid=${id} >Add to cart</button>`;
        
    } else {
        decrementBtns[i].nextElementSibling.textContent = Number(--quantity);
    }
}

/* } */