// Share Modal Helper - Safe & Defensive Implementation
document.addEventListener('DOMContentLoaded', function() {
    const shareBtn = document.getElementById('share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const shareModal = document.getElementById('share-modal');
            if (shareModal) {
                shareModal.classList.toggle('hidden');
            }
        });
    }

    const closeShareBtn = document.getElementById('close-share-btn');
    if (closeShareBtn) {
        closeShareBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const shareModal = document.getElementById('share-modal');
            if (shareModal) {
                shareModal.classList.add('hidden');
            }
        });
    }
});
