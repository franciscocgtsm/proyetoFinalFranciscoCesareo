
document.addEventListener("DOMContentLoaded", function () {
    if (!document.cookie.includes("visita")) {
        alert("Bienvenido a la Tienda Online");
        let fecha = new Date();
        fecha.setTime(fecha.getTime() + (24 * 60 * 60 * 1000)); // 1 día
        document.cookie = "visita=1; expires=" + fecha.toUTCString() + "; path=/";
    }
});
