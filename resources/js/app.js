import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Helper to extract YouTube ID from client-side input
window.extractYouTubeId = function(url) {
    if (!url) return null;
    url = url.trim();
    if (/^[a-zA-Z0-9_-]{11}$/.test(url)) return url;
    const match = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/i);
    return match ? match[1] : null;
};

Alpine.start();
