const cosuletContainer = document.getElementById("cos-articole");
const subtotalElement = document.getElementById("subtotal");
const transportElement = document.getElementById("transport");
const totalFinalElement = document.getElementById("total-final");

const COST_TRANSPORT = 15.00;


async function sendCartRequest(endpoint, data = {}) {
    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        });

        if (!response.ok) {
            throw new Error(`Eroare HTTP: ${response.status}`);
        }

        return await response.json();

    } catch (error) {
        console.error('Eroare la comunicarea cu serverul:', error);
        alert('A apărut o eroare la procesarea coșului. Încercați să vă reautentificați.');
        return { success: false, items: [] };
    }
}

async function renderCart() {
    cosuletContainer.innerHTML = '<p>Se încarcă coșul...</p>';

    const data = await sendCartRequest('backend/cart_actions.php', { action: 'get_items' });

    if (!data.success || data.items.length === 0) {
        const message = data.message && data.message.includes('autentificat')
            ? '<p>Vă rugăm să vă <a href="login.php">autentificați</a> pentru a vedea coșul.</p>'
            : '<p>Coșul tău este gol.</p>';

        cosuletContainer.innerHTML = message;
        updateSummary(0);
        return;
    }

    let cartHTML = '';
    let subtotalValue = 0;

    data.items.forEach(item => {
        const productID = item.ID_Produs;

        const priceClean = parseFloat(item.Pret_Vanzare);
        const lineTotal = (priceClean * item.Cantitate).toFixed(2).replace('.', ',');
        subtotalValue += priceClean * item.Cantitate;

        const oldPriceDisplay = item.Pret_Vechi
            ? `<span class="cart-old-price"><del>${parseFloat(item.Pret_Vechi).toFixed(2).replace('.', ',')} lei</del></span>`
            : '';

        cartHTML += `
            <div class="cart-item">
                <img src="${item.Imagine_URL}" alt="${item.Nume_Produs}" class="cart-item-img">
                <div class="item-details">
                    <h4>${item.Nume_Produs}</h4>
                    <div class="item-price">
                        ${oldPriceDisplay}
                        <span class="current-price">${priceClean.toFixed(2).replace('.', ',')} lei</span>
                    </div>
                </div>
                <div class="item-quantity">
                    <button onclick="changeQuantity(${productID}, -1)">-</button>
                    <span>${item.Cantitate}</span>
                    <button onclick="changeQuantity(${productID}, 1)">+</button>
                </div>
                <div class="item-total">
                    ${lineTotal} lei
                </div>
                <button class="remove-item-button" onclick="removeItem(${productID})">❌</button>
            </div>
            <hr>
        `;
    });

    cosuletContainer.innerHTML = cartHTML;
    updateSummary(subtotalValue);
}

function updateSummary(subtotal) {
    const subtotalFormatted = subtotal.toFixed(2).replace('.', ',');
    const transportCost = subtotal > 150 ? 0 : COST_TRANSPORT;
    const totalFinal = subtotal + transportCost;

    subtotalElement.innerHTML = `Subtotal: ${subtotalFormatted} lei`;
    transportElement.innerHTML = `Transport: ${transportCost.toFixed(2).replace('.', ',')} lei`;
    totalFinalElement.innerHTML = `TOTAL: ${totalFinal.toFixed(2).replace('.', ',')} lei`;
}

async function removeItem(productID) {
    const data = await sendCartRequest('backend/cart_actions.php', {
        action: 'remove_item',
        product_id: productID
    });

    if (data.success) {
        renderCart();
    }
}

async function changeQuantity(productID, delta) {
    const cartData = await sendCartRequest('backend/cart_actions.php', { action: 'get_items' });
    if (!cartData.success) return;

    const itemToUpdate = cartData.items.find(item => item.ID_Produs === productID);

    if (itemToUpdate) {
        const new_quantity = itemToUpdate.Cantitate + delta;

        if (new_quantity <= 0) {
            removeItem(productID);
            return;
        }

        const data = await sendCartRequest('backend/cart_actions.php', {
            action: 'update_quantity',
            product_id: productID,
            quantity: new_quantity
        });

        if (data.success) {
            renderCart();
        }
    }
}

document.addEventListener('DOMContentLoaded', renderCart);