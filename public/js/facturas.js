document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#modalCrearFactura form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                alert('Factura creada exitosamente');
                // Cerrar el modal
                let modal = bootstrap.Modal.getInstance(document.getElementById('modalCrearFactura'));
                modal.hide();
                // Opcional: recargar la página o actualizar la lista de facturas
                window.location.reload();
            } else {
                // Mostrar error
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    });
}); 