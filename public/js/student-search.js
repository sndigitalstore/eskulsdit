document.addEventListener("DOMContentLoaded", function() {
    // Live Search Logic
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    
    // Check if elements exist to avoid errors on pages without search
    if (!searchInput || !searchSuggestions) return;

    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value;
        const apiBaseUrl = searchInput.dataset.apiUrl; // Get URL from data attribute
        
        if (query.length < 2) {
            searchSuggestions.style.display = 'none';
            return;
        }

        timeoutId = setTimeout(() => {
            fetch(`${apiBaseUrl}?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let html = '';
                        data.forEach(student => {
                            // Extract first letter for avatar
                            const initial = student.name.charAt(0).toUpperCase();
                            const nis = student.nis || '-';
                            
                            html += `
                                <div class="suggestion-item" onclick="selectSuggestion('${student.name}')">
                                    <div style="width: 30px; height: 30px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">
                                        ${initial}
                                    </div>
                                    <div class="suggestion-info">
                                        <h4>${student.name}</h4>
                                        <p>NIS: ${nis} | Kelas: ${student.class}</p>
                                    </div>
                                </div>
                            `;
                        });
                        searchSuggestions.innerHTML = html;
                        searchSuggestions.style.display = 'block';
                    } else {
                        searchSuggestions.style.display = 'none';
                    }
                })
                .catch(err => {
                    console.error('Search Error:', err);
                    searchSuggestions.style.display = 'none';
                });
        }, 300);
    });

    // Hide when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== searchInput && e.target !== searchSuggestions) {
            searchSuggestions.style.display = 'none';
        }
    });
});

// Global function needs to be on window to be called from onclick in HTML string
window.selectSuggestion = function(name) {
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    
    searchInput.value = name;
    searchSuggestions.style.display = 'none';
    searchInput.closest('form').submit();
};
