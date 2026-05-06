/**
 * WhatsApp Click Tracker
 * 
 * Tracks WhatsApp button clicks for analytics
 * Rate Limited: 5 requests per minute per session (backend)
 * Throttled: 2000ms (frontend)
 * 
 * Usage:
 * <a href="..." @click="trackWhatsAppClick('navbar')">
 * <a href="..." @click="trackWhatsAppClick('katalog', 123, 'Product Name')">
 */

window.trackWhatsAppClick = function(source, productId = null, productName = null) {
    // Send tracking request to backend
    fetch('/track/whatsapp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            source: source,
            product_id: productId,
            product_name: productName,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('WhatsApp click tracked:', source);
        }
    })
    .catch(error => {
        // Silently fail - don't block user from opening WhatsApp
        console.warn('Failed to track WhatsApp click:', error);
    });
};

/**
 * Alpine.js Magic Helper
 * 
 * Usage in Alpine components:
 * <div x-data="{ trackWA: $trackWhatsApp }">
 *   <a @click="trackWA('navbar')">
 */
if (typeof Alpine !== 'undefined') {
    Alpine.magic('trackWhatsApp', () => {
        return (source, productId = null, productName = null) => {
            window.trackWhatsAppClick(source, productId, productName);
        };
    });
}
