// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Show/hide card digits input based on payment method
    const paymentMethodSelect = document.getElementById('payment_method');
    const cardContainer = document.getElementById('card-digits-container');
    const cardInput = document.getElementById('card_last4');
    
    if (paymentMethodSelect && cardContainer && cardInput) {
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'Credit Card') {
                cardContainer.style.display = 'block';
                cardInput.required = true;
            } else {
                cardContainer.style.display = 'none';
                cardInput.required = false;
                cardInput.value = ''; // Clear the value
            }
        });

        // Validate that only numbers are entered
        cardInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
        });
    }

    // Your existing functions below...
});

function addItem() {
    const tableBody = document.getElementById('invoice-items').querySelector('tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" name="description[]" required></td>
        <td><input type="number" name="qty[]" min="1" value="1" required></td>
        <td><input type="number" name="price[]" step="0.01" min="0" required></td>
        <td>
            <select name="tax_type[]">
                <option value="0">None</option>
                <option value="HST">HST</option>
                <option value="PST">PST</option>
                <option value="QST">QST</option>
                <option value="GST">GST</option>
            </select>
        </td>
        <td class="row-total">0.00</td>
        <td><button type="button" class="remove-item">Remove</button></td>
    `;
    tableBody.appendChild(newRow);
    attachListeners(newRow);
    updateTotals();

    const descriptionInput = newRow.querySelector('input[name="description[]"]');
    descriptionInput.focus();
    newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Attach listeners to a row
function attachListeners(row) {
    const inputs = row.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', updateTotals);
        input.addEventListener('change', updateTotals);
    });

    // Remove button
    const removeBtn = row.querySelector('.remove-item');
    if (removeBtn) {
        removeBtn.addEventListener('click', () => {
            row.remove();
            updateTotals();
        });
    }
}

// Initial listener attachment for existing rows
document.querySelectorAll('#invoice-items tbody tr').forEach(attachListeners);

function updateTotals() {
    let grandTotal = 0;

    document.querySelectorAll('#invoice-items tbody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
        const price = parseFloat(row.querySelector('input[name="price[]"]').value) || 0;
        const taxType = row.querySelector('select[name="tax_type[]"]').value;

        let total = qty * price;

        let taxRate = 0;
        switch(taxType) {
            case "HST": taxRate = 0.13; break;
            case "PST": taxRate = 0.08; break;
            case "QST": taxRate = 0.09975; break;
            case "GST": taxRate = 0.05; break;
            default: taxRate = 0; break;
        }

        total *= (1 + taxRate);
        row.querySelector('.row-total').textContent = total.toFixed(2);
        grandTotal += total;
    });

    document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
}