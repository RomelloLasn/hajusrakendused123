/**
 * Stripe helper functions
 */

function displayError(element, message) {
    const displayError = document.getElementById(element);
    displayError.textContent = message;
    displayError.classList.remove('d-none');
}

function clearError(element) {
    const displayError = document.getElementById(element);
    displayError.textContent = '';
    displayError.classList.add('d-none');
}

function startProcessing(buttonId, spinnerId, textId, processingText) {
    const button = document.getElementById(buttonId);
    const spinner = document.getElementById(spinnerId);
    const buttonText = document.getElementById(textId);
    
    button.disabled = true;
    spinner.classList.remove('d-none');
    buttonText.textContent = processingText || 'Processing...';
}

function stopProcessing(buttonId, spinnerId, textId, originalText) {
    const button = document.getElementById(buttonId);
    const spinner = document.getElementById(spinnerId);
    const buttonText = document.getElementById(textId);
    
    button.disabled = false;
    spinner.classList.add('d-none');
    buttonText.textContent = originalText;
}

function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(amount);
} 