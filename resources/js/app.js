import './bootstrap';
import 'preline'

document.addEventListener('livewire:navigated', () => {
    window.HSStaticMethods.autoInit();
})

window.onload = function() {
    // Reset the form fields when the page loads
    document.getElementById("form").reset();
};