const messageInput = document.getElementById('message');
const messageCount = document.getElementById('message-count');

const maxWords = 1000;
const maxChars = 5000;

// Initialize counter
messageCount.textContent = `Characters remaining: ${maxChars}`;

messageInput.addEventListener('input', () => {
    let text = messageInput.value;

    // Count words properly - handle empty text
    let words = text.trim() === '' ? [] : text.trim().split(/\s+/).filter(word => word.length > 0);
    
    // Trim to max words
    if (words.length > maxWords) {
        words = words.slice(0, maxWords);
        text = words.join(' ');
    }

    // Trim to max characters
    if (text.length > maxChars) {
        text = text.slice(0, maxChars);
        // Recount words after character trimming
        words = text.trim() === '' ? [] : text.trim().split(/\s+/).filter(word => word.length > 0);
    }

    messageInput.value = text;

    // Update live counter - show characters remaining
    const remainingChars = maxChars - text.length;
    messageCount.textContent = `Characters remaining: ${remainingChars}`;

    // Auto-resize
    messageInput.style.height = 'auto';
    messageInput.style.height = messageInput.scrollHeight + 'px';
});