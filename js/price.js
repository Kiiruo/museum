document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const excursionSelect = document.getElementById('excursion');
    const totalPriceElement = document.getElementById('totalPrice');

    function calculateTotal() {
        const selectedOption = excursionSelect.options[excursionSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const quantity = parseInt(quantityInput.value);
        
        const total = price * quantity;

        totalPriceElement.textContent = total;
    }

    quantityInput.addEventListener('input', calculateTotal);
    excursionSelect.addEventListener('change', calculateTotal);
    
    // Инициализация значения при загрузке страницы
    calculateTotal();
});
