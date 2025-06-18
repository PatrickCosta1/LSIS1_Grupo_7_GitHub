document.addEventListener('DOMContentLoaded', function () {
    const openBtn = document.getElementById('open-chatbot');
    const iframe = document.getElementById('chatbot-iframe');
    let isOpen = false;
    openBtn.onclick = function() {
        isOpen = !isOpen;
        iframe.style.display = isOpen ? 'block' : 'none';
        openBtn.innerText = isOpen ? '×' : '?';
    };
});