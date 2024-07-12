let genderData = [];
let categoryData = [];
let brandData = [];
let sortValue = "";
let showPerPage = 6;
let allItems;
let subTotal = 0;
let page = 0;
let string = "";
let user_id;
if (document.querySelector(".user_tag") != null) {
  user_id = document.querySelector(".user_tag").dataset.id;
}

//BLOKS
let productContainer = document.querySelector(".product-container");
let cartListContainer = document.querySelector(".cart_list");
let blockError = document.querySelector(".blk_error");

//Pagination buttons
let next = document.querySelectorAll(".next-arrow");
let back = document.querySelectorAll(".prev-arrow");
function ValidateLogin() {
  //Elements
  let email = document.querySelector("#email");
  let password = document.querySelector("#password");
  //Regex
  let emailRegex = /^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,}$/;
  let passwordRegex =
    /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;

  //Error div
  let error = document.querySelector(".error");
  let numErrors = 0;
  //Validation
  if (!emailRegex.test(email.value)) {
    numErrors++;
    error.classList.add("alert");
    error.classList.add("alert-danger");
    error.innerHTML += `<p>Invalid email address! example: yourname@gmail.com</p>`;
  } else {
    error.classList.remove("alert");
    error.classList.remove("alert-danger");
    error.innerHTML = ``;
  }
  if (!passwordRegex.test(password.value)) {
    numErrors++;
    error.classList.add("alert");
    error.classList.add("alert-danger");
    error.innerHTML += `<p>Invalid password! example: Password02</p>`;
  } else {
    error.classList.remove("alert");
    error.classList.remove("alert-danger");
    error.innerHTML = ``;
  }
  if (numErrors > 0) {
    return false;
  }
  return true;
}
function validateUserChanges() {
  let errorBlock = document.querySelector(".errors");
  errorBlock.innerHTML = "";
  let fisrt_name = document.querySelector("#fName");
  let last_name = document.querySelector("#lName");
  let email = document.querySelector("#email");
  let password = document.querySelector("#password");

  let emailRegex = /^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,}$/;
  let passwordRegex =
    /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;

  let greske = 0;

  if (!emailRegex.test(email.value)) {
    greske++;
    errorBlock.classList.add("alert-danger");
    errorBlock.classList.add("alert");
    errorBlock.innerHTML += "<p>Please insert correct email</p>";
  }
  if (password.value != "") {
    if (!passwordRegex.test(password.value)) {
      greske++;
      errorBlock.classList.add("alert-danger");
      errorBlock.classList.add("alert");
      errorBlock.innerHTML +=
        "<p>Please insert correct password! example: Password02</p>";
    }
  }
  if (fisrt_name.value == "") {
    greske++;
    errorBlock.classList.add("alert-danger");
    errorBlock.classList.add("alert");
    errorBlock.innerHTML += "<p>Please insert first name</p>";
  }
  if (last_name.value == "") {
    greske++;
    errorBlock.classList.add("alert-danger");
    errorBlock.classList.add("alert");
    errorBlock.innerHTML += "<p>Please insert last name</p>";
  }

  if (greske > 0) return false;
  return true;
}
window.addEventListener("load", function () {
  if (window.location.search.includes("register")) {
    //Elements
    let registerBtn = document.querySelector(".register-btn");

    //Events
    registerBtn.addEventListener("click", function (e) {
      validateForm(e);
    });
  } else if (window.location.search.includes("login")) {
    let error = document.querySelector(".error");
    error.innerHTML = ``;

    let login_btn = document.querySelector("#loginBtn");
    login_btn.addEventListener("click", function (e) {
      e.preventDefault();
      let email = document.querySelector("#email");
      let password = document.querySelector("#password");
      let emailRegex = /^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,}$/;
      let passwordRegex =
       /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;

      let numErrors = 0;
      if (!emailRegex.test(email.value)) {
        numErrors++;
        error.classList.add("alert");
        error.classList.add("alert-danger");
        error.innerHTML += `<p>Invalid email address! example: yourname@gmail.com</p>`;
      } else {
        error.classList.remove("alert");
        error.classList.remove("alert-danger");
        error.innerHTML = ``;
      }
      if (!passwordRegex.test(password.value)) {
        numErrors++;
        error.classList.add("alert");
        error.classList.add("alert-danger");
        error.innerHTML += `<p>Invalid password! example: Password02</p>`;
      } else {
        error.classList.remove("alert");
        error.classList.remove("alert-danger");
        error.innerHTML = ``;
      }

      if (numErrors === 0) {
        data = {
          email: email.value,
          password: password.value,
        };
        ajaxCallBack(
          "models/login.php",
          "POST",
          data,
          function (result) {
            if (result) window.location.href = "index.php?page=home";
          },
          function (xhr) {
            error.classList.add("alert");
            error.classList.add("alert-danger");
            error.innerHTML += `<p>${xhr.responseJSON}</p>`;
          }
        );
      }
    });
  } else if (window.location.search.includes("shop")) {
    ajaxCallBack(
      "models/getAllItems.php?item=product&limit=true",
      "GET",
      null,
      function (data) {
        ispisProizvoda(data);
      },
      function (xhr) {
        console.log(xhr);
      }
    );

    //FILTER
    let genders = $(".gender");
    genders.on("change", function (e) {
      page = 0;
      validateFilters(e, genderData, "gender");
    });
    let brands = $(".brand");
    brands.on("change", function (e) {
      page = 0;
      validateFilters(e, brandData, "brand");
    });
    let categories = $(".category");
    categories.on("change", function (e) {
      page = 0;
      validateFilters(e, categoryData, "category");
    });

    //SORT
    let sort = $("#sort");
    sort.on("change", function (e) {
      page = 0;
      sortValue = e.target.value;

      finallyFilter();
    });
    
    //Search
    let search = $("#search_input");
    search.on("keyup", function (e) {
      string = e.target.value;
      string = string.toLowerCase();
      finallyFilter();
    });
    
    //SHOW PER PAGE
    let perPagesDDL = $(".perPage");
    $.each(perPagesDDL, function (perPage) {
      perPagesDDL[perPage].addEventListener("change", function (e) {
        page = 0;
        showPerPage = e.target.value;
        finallyFilter();
      });
    });

    //Next Page
    $(".next-arrow").on("click", function (e) {
      e.preventDefault();
      page++;
      finallyFilter();
    });
    $(".prev-arrow").on("click", function (e) {
      e.preventDefault();
      if (page < 1) {
        page = 0;
      }
      page--;
      finallyFilter();
    });
  } else if (window.location.search.includes("single-product")) {
    //Elements
    let sizes = document.querySelectorAll(".size");
    let addToCart = document.querySelector("#addToCart");

    //Display sizes and choose one
    sizes.forEach(function (size) {
      size.addEventListener("click", function (e) {
        let size = e.target;
        removeClasses();
        size.classList.remove("btn-custom");
        size.classList.add("btn-secondary");
      });
    });

    function removeClasses() {
      sizes.forEach(function (size) {
        size.classList.remove("btn-secondary");
        size.classList.add("btn-custom");
      });
    }

    //Add To Cart
    addToCart.addEventListener("click", function (e) {
      e.preventDefault();
      let productId = e.target.dataset.id;
      let qty = $(".qty").val();
      let sizeId;
      sizes.forEach(function (size) {
        if (size.classList.contains("btn-secondary")) {
          sizeId = size.dataset.id;
        }
      });
      if (sizeId == undefined) {
        //Uraditi upozorenje
        blockError.innerHTML = `
        <div class="alert alert-danger" role="alert">
        Please select size!
        </div>`;
      } else {
        blockError.innerHTML = "";
        data = {
          product_id: productId,
          user_id: user_id,
          qty: qty,
          size_id: sizeId,
        };
        ajaxCallBack(
          "models/insert.php?table=cart",
          "POST",
          data,
          function (response) {
            popUpWindow("Product added to cart!", "popUpAdd", "noneAdd");
          },
          function (xhr) {
            console.log(xhr);
          }
        );
      }
    });
  } else if (window.location.search.includes("cart")) {
    //Load product in cart
    ajaxCallBack(
      "models/getAllItems.php?item=cart&id=" + user_id,
      "GET",
      null,
      function (response) {
        displayAllItemsFromCart(response);
      },
      function (xhr) {
        console.log(xhr);
      }
    );

    //Proceed to checkout
    let checkout = $(".shopBtn");
    checkout.on("click", function (e) {
      e.preventDefault();

      let orders = [];

      //Get orders
      $.each(allItems, function (i) {
        let product_id = allItems[i].querySelector(".item_name").dataset.id;
        let size_id = allItems[i].querySelector(".size").dataset.id;
        let price_id = allItems[i].querySelector(".price").dataset.id;
        let qty = allItems[i].querySelector(".qty").value;
        let cart_id = allItems[i].dataset.cart;
        order = {
          product_id: product_id,
          size_id: size_id,
          price_id: price_id,
          qty: qty,
          cart_id: cart_id,
        };
        orders.push(order);
      });

      //Get location
      let country = $("#country").val();
      let city = $("#city").val();
      let address = $("#address").val();
      let location = {
        country: country,
        city: city,
        address: address,
      };
      let finallOrder = {
        orders: orders,
        location: location,
        user_id: user_id,
        total: subTotal,
      };
      console.log(finallOrder);
      if (country == "" || city == "" || address == "") {
        //Ispisati gresku
        blockError.innerHTML = `
        <div class="alert alert-danger" role="alert">
        Please insert your shipping address!
        </div>`;
      } else {
        blockError.innerHTML = "";
        ajaxCallBack(
          "models/insert.php?table=order",
          "POST",
          finallOrder,
          function (data) {
            ajaxCallBack(
              "models/getAllItems.php?item=cart&id=" + user_id,
              "GET",
              null,
              function (response) {
                displayAllItemsFromCart(response);
              },
              function (xhr) {
                console.log(xhr);
              }
            );
            popUpWindow("Purchase completed!", "popUpWindow", "none");
          },
          function (xhr) {
            console.log(xhr);
          }
        );
      }
    });

    //REMOVE PRODUCT FROM CART
    $(document).on("click", ".removeProdFromCart", function (e) {
      let cart_id = e.target.dataset.cart;
      data = {
        cart_item_id: cart_id,
      };
      ajaxCallBack(
        "models/removeFromCart.php",
        "POST",
        data,
        function (data) {
          ajaxCallBack(
            "models/getAllItems.php?item=cart&id=" + user_id,
            "GET",
            null,
            function (response) {
              displayAllItemsFromCart(response);
            },
            function (xhr) {
              console.log(xhr);
            }
          );
        },
        function (error) {
          console.log(error);
        }
      );
    });
    $(document).on("change", '.qty', function(e){
        let qty = e.target.value;
        let parent = e.target.closest('.single-item')
        let price = parent.querySelector('.price').textContent;
        price = price.substring(1, price.length);
        price = parseInt(price);
        
        let priceTotal = parent.querySelector('.totalProductPrice');
        let finallPrice = price*qty;
        finallPrice = finallPrice.toFixed(2)
        priceTotal.textContent = `$${finallPrice}`
        totalPriceCalculate();
        
    })
  }
  function totalPriceCalculate(){
      let totalPrice = document.querySelectorAll('.totalProductPrice');
      let finallPrice = 0;
      totalPrice.forEach((price)=>{
          let singleProductPrice = price.textContent;
          singleProductPrice = singleProductPrice.substring(1, price.length);
          singleProductPrice = parseInt(singleProductPrice);
          finallPrice+= singleProductPrice;
      })
      finallprice = finallPrice+20;
      finallprice = finallprice.toFixed(2);
      subTotal = finallprice;
      let totalBlock = document.querySelector('.subTotal');
      totalBlock.textContent = `$${finallprice} - ($20 shipping)`
  }
});

function validateForm(e) {
  e.preventDefault();
  //Errors number
  let errorsNumber = 0;
  //input fields
  let firstName = document.querySelector("#firstN");
  let lastName = document.querySelector("#lastN");
  let email = document.querySelector("#email");
  let password = document.querySelector("#password");
  let confirmPassword = document.querySelector("#passwordConfirm");

  //Regex
  let nameRegex = /^[A-Za-z]{2,}$/;
  let emailRegex = /^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,}$/;
  let passwordRegex =
    /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;

  //validate input fields
  validateInputFields(
    firstName,
    nameRegex,
    "Please enter a First name",
    errorsNumber
  );
  validateInputFields(
    lastName,
    nameRegex,
    "Please enter a Last name",
    errorsNumber
  );
  validateInputFields(
    email,
    emailRegex,
    "Invalid email! example (name@example.com)",
    errorsNumber
  );
  validateInputFields(
    password,
    passwordRegex,
    "Invalid password! Must be at least 8 characters long. At least one uppercase letter, one lowercase letter, one number",
    errorsNumber
  );

  //Confirm password matching
  let cp = confirmPassword.parentElement.querySelector(".error");
  if (
    confirmPassword.value !== password.value ||
    confirmPassword.value.length === 0
  ) {
    cp.textContent = "Password does not match";
    confirmPassword.classList.add("warrning");
    errorsNumber++;
  } else {
    cp.textContent = "";
    confirmPassword.classList.remove("warrning");
  }

  if (errorsNumber === 0) {
    data = {
      firstName: firstName.value,
      lastName: lastName.value,
      email: email.value,
      password: password.value,
    };
    ajaxCallBack(
      "models/registerUser.php",
      "POST",
      data,
      function (data) {
        document.querySelector(
          ".success"
        ).innerHTML = `<p class="text-success">${data}</p>`;
      },
      function (xhr) {
            document.querySelector(
          ".success"
        ).innerHTML = `<p class="text-danger">${xhr.responseJSON}</p>`;

      }
    );
  }
}
function validateInputFields(field, regex, message, errorsNumber) {
  let errorBlock = field.parentElement.querySelector(".error");
  if (!regex.test(field.value)) {
    errorsNumber++;
    errorBlock.innerHTML = message;
    field.classList.add("warrning");
  } else {
    errorBlock.innerHTML = "";
    field.classList.remove("warrning");
  }
}
function ajaxCallBack(url, method, data, result, error) {
  $.ajax({
    url: url,
    method: method,
    dataType: "json",
    data: data,
    success: result,
    error: error,
  });
}

function ispisProizvoda(products, productsNumber = null) {
  //Max page number
  let pages;
  let currentPage = page + 1;
  if (productsNumber !== null) {
    pages = productsNumber / showPerPage;
    pages = Math.ceil(pages);
  }
  //Pagination settings
  if (productsNumber != null && currentPage == pages) {
    next.forEach((btn) => {
      btn.classList.add("disabled");
    });
  } else {
    next.forEach((btn) => {
      btn.classList.remove("disabled");
    });
  }
  if (page === 0) {
    back.forEach((btn) => {
      btn.classList.add("disabled");
    });
  } else {
    back.forEach((btn) => {
      btn.classList.remove("disabled");
    });
  }

  productContainer.innerHTML = ``;
  
  products.forEach((prod) => {
    let discount =
      prod.percentage != 0
        ? prod.price - (prod.price * prod.percentage) / 100
        : prod.price;
    discount = Number(discount);

    productContainer.innerHTML += `<div class="col-lg-4 col-md-6">
    <div class="single-product">
      <img class="img-fluid thumbnail" src="assets/ProductImages/thumbnails/${
        prod.mainImg
      }" alt="">
      <div class="product-details">
        <h6 class='productNameBlock'>${prod.name}</h6>
        <div class="price">
          <h6>$${discount.toFixed(2)}</h6>
          ${
            prod.percentage != 0
              ? `<h6 class="l-through">${prod.price}</h6>`
              : ""
          }
          
        </div>
        <div class="prd-bottom">
          <a href="index.php?page=single-product&prodid=${
            prod.product_id
          }" class="social-info" >
          <span class="lnr lnr-move" data-id='${prod.product_id}'></span>
            <p class="hover-text" '>view more</p>
          </a>
        </div>
      </div>
    </div>
  </div>`;
  });
}

function displayAllItemsFromCart(response) {
  cartListContainer.innerHTML = "";
  console.log(response.length);
  if (response.length > 0) {
    response.forEach(function (item) {
      let discount =
        item.percentage !== null
          ? item.price - (item.price * item.percentage) / 100
          : item.price;
      discount = Number(discount);
      discount = discount.toFixed(2);
      cartListContainer.innerHTML += `
            <tr class='single-item' data-cart="${item.cart_id}">
            <td>
                <div class="media">
                    <div class="d-flex">
                        <img src="assets/ProductImages/thumbnails/${
                          item.mainImg
                        }" height='80' alt="">
                    </div>
                    <div class="media-body">
                        <p class='item_name' data-id="${item.product_id}">${
        item.name
      }</p>
                    </div>
                </div>
            </td>
            <td>
                <h5 class="price" data-id="${item.price_id}">$${discount}</h5>
            </td>
            <td>
                <h5 class="size" data-id="${item.size_id}">${item.size}</h5>
            </td>
            <td>
                <div class="product_count">
                        <input type="number" name="qty" value="${
                          item.quantity
                        }" min="1" max='${
        item.lagetQty
      }' title="Quantity:" class="input-text qty">
                </div>
            </td>
            <td>
                <h5 class='totalProductPrice'>$${(discount * item.quantity).toFixed(2)}</h5>
            </td>
            <td>
              <img src="assets/img/close.png" class='removeProdFromCart' alt='close img' data-cart='${
                item.cart_item_id
              }'>
            </td>
        </tr>
            `;
      subTotal += discount * item.quantity + 20;
    });

    cartListContainer.innerHTML += `
              <tr>
                  <td>
  
                  </td>
                  <td>
  
                  </td>
                  <td>
                      <h5>Subtotal</h5>
                  </td>
                  <td>
                      <h5 class='subTotal'>$${subTotal.toFixed(2)} - ($20 shipping)</h5>
                  </td>
              </tr>
              
                  `;
  } else {
    document.querySelector(".cart-ct").innerHTML = `
        <div class="alert alert-danger" role="alert">
          Empty Cart!
        </div>
  `;
  }
  allItems = document.querySelectorAll(".single-item");
}

function validateFilters(e, array, type) {
  let cbValue = e.target.dataset.id;
  if (e.target.checked) {
    if (!array.includes(cbValue)) array.push(cbValue);
  } else {
    if (type == "category")
      categoryData = categoryData.filter((x) => x != cbValue);
    if (type == "brand") brandData = brandData.filter((x) => x !== cbValue);
    if (type == "gender") genderData = genderData.filter((x) => x !== cbValue);
  }

  finallyFilter();
}

function finallyFilter() {
  let data = {
    genders: genderData,
    categories: categoryData,
    brands: brandData,
    sort: sortValue,
    showPerPage: showPerPage,
    search: string,
    page: page,
  };
  console.log(data);
  ajaxCallBack(
    "models/filter-sort-products.php",
    "POST",
    data,
    function (data) {
        if(data.products) {
            ispisProizvoda(data.products, data.number);
        }else {
            productContainer.innerHTML = `<h3 class="text-danger pt-3 pb-3">Sorry we currently don't have this product</h3>`
        }
      
    },
    function (xhr) {
      productContainer.innerHTML = `<h3 class="text-danger pt-3 pb-3">${xhr.responseText}</h3>`;
      next.forEach((btn) => {
        btn.classList.add("disabled");
      });
    }
  );
}

function popUpWindow(message, className, none) {
  console.log("usao");
  let popUpWindow = document.querySelector(".popup");
  popUpWindow.innerHTML = "";
  popUpWindow.classList.remove(none);
  popUpWindow.classList.add(className);
  popUpWindow.innerHTML = `${message}`;
  setTimeout(function () {
    popUpWindow.classList.add(none);
    popUpWindow.classList.remove(className);
  }, 3000);
}
