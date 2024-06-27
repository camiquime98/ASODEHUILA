document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.php-email-form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const loadingElement = form.querySelector('.loading');
        const errorElement = form.querySelector('.error-message');
        const sentElement = form.querySelector('.sent-message');
        
        loadingElement.style.display = 'block';
        errorElement.style.display = 'none';
        sentElement.style.display = 'none';
        
        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loadingElement.style.display = 'none';
            if (data.success) {
                sentElement.style.display = 'block';
                form.reset();
            } else {
                errorElement.textContent = data.message || 'Ocurrió un error al enviar el mensaje. Por favor, intenta de nuevo.';
                errorElement.style.display = 'block';
            }
        })
        .catch(error => {
            loadingElement.style.display = 'none';
            errorElement.textContent = 'Ocurrió un error al enviar el mensaje. Por favor, intenta de nuevo.';
            errorElement.style.display = 'block';
            console.error('Error:', error);
        });
    });
});