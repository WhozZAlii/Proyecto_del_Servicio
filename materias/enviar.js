// =====================================================================
// enviar.js - Envía el formulario a guardar.php (base de datos)
// No modifica Menuinteractivointeracciones.js: solo añade un listener
// extra al submit del formulario. Debe cargarse DESPUÉS del script original.
// =====================================================================
(function () {
    const form = document.getElementById('formMateria');
    if (!form) return;

    form.addEventListener('submit', async function () {
        // Mismas condiciones que el script original: si no pasan, no se envía nada
        if (typeof validarPaso === 'function' && !validarPaso(paso3)) return;
        if (typeof recalcularPonderacion === 'function' && recalcularPonderacion() !== 100) return;

        const boton = document.getElementById('btnGuardar');
        if (boton) boton.disabled = true;

        try {
            const respuesta = await fetch('guardar.php', {
                method: 'POST',
                body: new FormData(form)
            });

            let datos;
            try {
                datos = await respuesta.json();
            } catch (_) {
                throw new Error('El servidor no devolvió una respuesta válida.');
            }

            if (!respuesta.ok || !datos.ok) {
                throw new Error(datos.mensaje || 'Error desconocido.');
            }
            alert('Guardado en la base de datos (ID ' + datos.id + ').');
        } catch (error) {
            alert('No se pudo guardar en la base de datos: ' + error.message);
        } finally {
            if (boton) boton.disabled = false;
        }
    });
})();