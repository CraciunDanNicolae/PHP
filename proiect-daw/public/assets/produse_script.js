let products = ALL_PRODUCTS || [];
const produseContainer = document.getElementById("produse-container");

renderProducts(products);
attachBuyButtonListeners();
attachSearchListener();

function renderProducts(productsToRender) {
    let produseHTML = ``;

    if (productsToRender.length === 0) {
        produseContainer.innerHTML = '<p>Nu au fost găsite produse care să se potrivească căutării.</p>';
        return;
    }

    productsToRender.forEach((produs) => {
        const productID = produs.ID_Produs;

        const numeProdus = produs.Nume_Produs;
        const pretProdus = (parseFloat(produs.Pret_Vanzare).toFixed(2)).replace('.', ',') + ' lei';

        const hasDiscount = produs.Pret_Vechi && produs.Procent_Reducere;
        const discountTag = hasDiscount
            ? `<span class="discount-tag">-${produs.Procent_Reducere}%</span>`
            : '';
        const priceDisplay = hasDiscount
            ? `<div class="pret-vechi"><del>${(parseFloat(produs.Pret_Vechi).toFixed(2)).replace('.', ',')} lei</del></div>
               <div class="pret pret-discount">${pretProdus}</div>`
            : `<div class="pret">${pretProdus}</div>`;

        const imageUrl = produs.Imagine_URL && produs.Imagine_URL.length > 0
            ? produs.Imagine_URL
            : 'placeholder.jpg';

        produseHTML += `<div class="produs" data-product-id="${productID}">
                            ${discountTag}
                            <img src="${imageUrl}" alt="${numeProdus}">
                            <h3>${numeProdus}</h3>
                            ${priceDisplay}
                            <button class="buy-button" data-product-id="${productID}">Adauga in cos</button>
                        </div>`;
    });

    produseContainer.innerHTML = produseHTML;
    attachBuyButtonListeners();
}

function attachBuyButtonListeners() {
    const buyButtons = document.querySelectorAll('.buy-button');
    buyButtons.forEach(button => {
        button.removeEventListener('click', handleBuyButtonClick);
        button.addEventListener('click', handleBuyButtonClick);
    });
}

function handleBuyButtonClick(event) {
    const productID = event.currentTarget.getAttribute('data-product-id');
    addProduct(parseInt(productID));
}

function attachSearchListener() {
    const searchBar = document.getElementById('search-bar');
    if (searchBar) {
        searchBar.addEventListener('input', () => {
            const searchTerm = searchBar.value.toLowerCase().trim();
            const filteredProducts = products.filter(product =>
                product.Nume_Produs.toLowerCase().includes(searchTerm)
            );
            renderProducts(filteredProducts);
        });
    }
}

function addProduct(productID) {
    fetch('backend/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'add_to_cart',
            product_id: productID
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert("Eroare la adăugare: " + data.message + ". Asigură-te că ești logat!");
            }
        })
        .catch(error => {
            console.error('Eroare rețea:', error);
            alert('Eroare la conectarea cu serverul.');
        });
}