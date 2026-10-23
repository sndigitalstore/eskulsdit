<style>
    /* ========================================================================= */
    /* UNIVERSAL CUSTOM SELECT & POPOVER (MODERN, MOBILE-FIRST, ZERO-SCREEN TAKEOVER) */
    /* ========================================================================= */
    .custom-select-wrapper {
        position: relative;
        width: 100%;
        font-family: inherit;
        box-sizing: border-box;
    }

    /* Native hidden select for form submission & validation */
    .custom-select-native-hidden {
        position: absolute !important;
        opacity: 0 !important;
        pointer-events: none !important;
        width: 1px !important;
        height: 1px !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        z-index: -1 !important;
    }

    /* Trigger Box Utama */
    .custom-select-trigger {
        width: 100%;
        min-height: 50px;
        border-radius: 14px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 8px 6px 14px;
        cursor: pointer;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        box-sizing: border-box;
        user-select: none;
        gap: 10px;
    }

    .custom-select-trigger:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }

    .custom-select-trigger:focus-visible {
        outline: none;
    }

    /* Konten Kiri (Icon + Teks) */
    .custom-select-left {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
        flex: 1;
        min-width: 0;
    }

    .custom-select-left .select-icon {
        font-size: 0.95rem;
        flex-shrink: 0;
        transition: color 0.2s ease;
    }

    .custom-select-label-text {
        font-size: 0.93rem;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .custom-select-label-text.is-placeholder {
        color: #94a3b8;
        font-weight: 500;
    }

    /* Kotak Tanda Panah (Sesuai Permintaan: Kotak Tanda Panah Khusus & Interaktif) */
    .custom-arrow-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        flex-shrink: 0;
        transition: all 0.22s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .custom-select-trigger:hover .custom-arrow-box {
        background: #e2e8f0;
        color: #1e293b;
    }

    .custom-chevron {
        font-size: 0.78rem;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Status Terbuka (Active State) */
    .custom-select-wrapper.is-open .custom-select-trigger {
        background: #ffffff;
    }

    .custom-select-wrapper.is-open .custom-arrow-box {
        transform: scale(1.02);
    }

    .custom-select-wrapper.is-open .custom-chevron {
        transform: rotate(180deg);
    }

    /* Popover Menu Dropdown (Menempel langsung di bawah kotak) */
    .custom-select-popover {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.16), 0 6px 16px rgba(0, 0, 0, 0.06);
        z-index: 1050;
        overflow: hidden;
        display: none;
        flex-direction: column;
        animation: customPopoverFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-sizing: border-box;
    }

    .custom-select-wrapper.is-open .custom-select-popover {
        display: flex;
    }

    @keyframes customPopoverFadeIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Kotak Pencarian Cepat di Dalam Popover */
    .custom-search-container {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .custom-search-container .search-icon {
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .custom-search-input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.88rem;
        font-weight: 600;
        color: #0f172a;
        padding: 4px 0;
    }

    .custom-search-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    /* Daftar Pilihan */
    .custom-options-list {
        max-height: 230px;
        overflow-y: auto;
        padding: 6px;
        -webkit-overflow-scrolling: touch;
    }

    .custom-options-list::-webkit-scrollbar {
        width: 5px;
    }
    .custom-options-list::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-options-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* Baris Opsi Pilihan */
    .custom-option-item {
        padding: 9px 12px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-bottom: 2px;
        user-select: none;
    }

    .custom-option-item:hover {
        background: #f1f5f9;
    }

    .custom-option-item.is-selected {
        font-weight: 700;
    }

    .option-left-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .option-main-text {
        font-size: 0.9rem;
        color: #1e293b;
        font-weight: 600;
        line-height: 1.3;
    }

    .option-sub-badge {
        font-size: 0.74rem;
        color: #64748b;
        font-weight: 500;
    }

    .custom-option-check {
        font-size: 0.82rem;
        opacity: 0;
        transform: scale(0.6);
        transition: all 0.18s ease;
        flex-shrink: 0;
    }

    .custom-option-item.is-selected .custom-option-check {
        opacity: 1;
        transform: scale(1);
    }

    .custom-empty-notice {
        padding: 16px 12px;
        text-align: center;
        font-size: 0.84rem;
        color: #94a3b8;
        font-weight: 500;
    }

    /* ==================== THEME COLOR VARIANTS ==================== */
    /* 1. Emerald (Presensi & Default) */
    .custom-select-wrapper[data-theme="emerald"] .custom-select-left .select-icon { color: #059669; }
    .custom-select-wrapper[data-theme="emerald"].is-open .custom-select-trigger {
        border-color: #059669;
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.14);
    }
    .custom-select-wrapper[data-theme="emerald"].is-open .custom-arrow-box {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #059669;
    }
    .custom-select-wrapper[data-theme="emerald"] .custom-option-item.is-selected {
        background: #ecfdf5;
        color: #047857;
    }
    .custom-select-wrapper[data-theme="emerald"] .custom-option-item.is-selected .option-main-text { color: #047857; }
    .custom-select-wrapper[data-theme="emerald"] .custom-option-item.is-selected .option-sub-badge { color: #059669; }
    .custom-select-wrapper[data-theme="emerald"] .custom-option-item.is-selected .custom-option-check { color: #059669; }

    /* 2. Purple / Indigo (Penilaian Siswa / Grades) */
    .custom-select-wrapper[data-theme="purple"] .custom-select-left .select-icon { color: #7c3aed; }
    .custom-select-wrapper[data-theme="purple"].is-open .custom-select-trigger {
        border-color: #7c3aed;
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.14);
    }
    .custom-select-wrapper[data-theme="purple"].is-open .custom-arrow-box {
        background: #f5f3ff;
        border-color: #ddd6fe;
        color: #7c3aed;
    }
    .custom-select-wrapper[data-theme="purple"] .custom-option-item.is-selected {
        background: #f5f3ff;
        color: #6d28d9;
    }
    .custom-select-wrapper[data-theme="purple"] .custom-option-item.is-selected .option-main-text { color: #6d28d9; }
    .custom-select-wrapper[data-theme="purple"] .custom-option-item.is-selected .option-sub-badge { color: #7c3aed; }
    .custom-select-wrapper[data-theme="purple"] .custom-option-item.is-selected .custom-option-check { color: #7c3aed; }

    /* 3. Blue / Sky (Guru / Absensi Pembina) */
    .custom-select-wrapper[data-theme="blue"] .custom-select-left .select-icon { color: #0284c7; }
    .custom-select-wrapper[data-theme="blue"].is-open .custom-select-trigger {
        border-color: #0284c7;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.14);
    }
    .custom-select-wrapper[data-theme="blue"].is-open .custom-arrow-box {
        background: #e0f2fe;
        border-color: #bae6fd;
        color: #0284c7;
    }
    .custom-select-wrapper[data-theme="blue"] .custom-option-item.is-selected {
        background: #f0f9ff;
        color: #0369a1;
    }
    .custom-select-wrapper[data-theme="blue"] .custom-option-item.is-selected .option-main-text { color: #0369a1; }
    .custom-select-wrapper[data-theme="blue"] .custom-option-item.is-selected .option-sub-badge { color: #0284c7; }
    .custom-select-wrapper[data-theme="blue"] .custom-option-item.is-selected .custom-option-check { color: #0284c7; }

    /* Responsive HP: Menempel langsung di bawah kotak tanda panah, tidak menutupi layar */
    @media (max-width: 640px) {
        .custom-select-trigger {
            min-height: 48px;
            padding: 4px 6px 4px 12px;
        }
        .custom-arrow-box {
            width: 34px;
            height: 34px;
        }
        .custom-select-popover {
            border-radius: 14px;
            max-height: 240px;
        }
        .custom-options-list {
            max-height: 190px;
        }
    }
</style>

<script>
(function() {
    /**
     * Inisialisasi Custom Select yang menempel langsung pada kotak tanda panah
     * @param {HTMLSelectElement} selectEl
     * @param {Object} options
     */
    window.initCustomSelect = function(selectEl, options = {}) {
        if (!selectEl || selectEl.dataset.customSelectInitialized === 'true') return;
        selectEl.dataset.customSelectInitialized = 'true';

        // Konfigurasi tema & ikon
        const theme = options.theme || selectEl.dataset.theme || 'emerald';
        const defaultIcon = options.icon || selectEl.dataset.icon || 'fas fa-chevron-circle-down';
        const placeholder = options.placeholder || selectEl.dataset.placeholder || '-- Pilih Opsi --';
        const searchPlaceholder = options.searchPlaceholder || selectEl.dataset.searchPlaceholder || 'Ketik untuk mencari...';

        // Sembunyikan select asli tanpa memutus validasi form
        selectEl.classList.add('custom-select-native-hidden');

        // Buat struktur DOM kustom
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';
        wrapper.setAttribute('data-theme', theme);
        if (selectEl.id) wrapper.id = 'custom-wrapper-' + selectEl.id;

        // Trigger Button
        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.setAttribute('tabindex', '0');
        trigger.setAttribute('role', 'combobox');
        trigger.setAttribute('aria-expanded', 'false');

        const leftContent = document.createElement('div');
        leftContent.className = 'custom-select-left';

        const iconEl = document.createElement('i');
        iconEl.className = defaultIcon + ' select-icon';

        const labelEl = document.createElement('span');
        labelEl.className = 'custom-select-label-text';

        leftContent.appendChild(iconEl);
        leftContent.appendChild(labelEl);

        const arrowBox = document.createElement('div');
        arrowBox.className = 'custom-arrow-box';
        arrowBox.setAttribute('title', 'Buka Pilihan');
        arrowBox.innerHTML = '<i class="fas fa-chevron-down custom-chevron"></i>';

        trigger.appendChild(leftContent);
        trigger.appendChild(arrowBox);

        // Popover List
        const popover = document.createElement('div');
        popover.className = 'custom-select-popover';

        // Filter / Search Bar (jika opsi > 4)
        let searchInput = null;
        const totalOptions = selectEl.options.length;
        if (totalOptions > 4) {
            const searchContainer = document.createElement('div');
            searchContainer.className = 'custom-search-container';
            searchContainer.innerHTML = `<i class="fas fa-search search-icon"></i><input type="text" class="custom-search-input" placeholder="${searchPlaceholder}" autocomplete="off">`;
            searchInput = searchContainer.querySelector('.custom-search-input');
            popover.appendChild(searchContainer);
        }

        const optionsList = document.createElement('div');
        optionsList.className = 'custom-options-list';
        popover.appendChild(optionsList);

        wrapper.appendChild(trigger);
        wrapper.appendChild(popover);

        // Sisipkan wrapper setelah select asli
        selectEl.parentNode.insertBefore(wrapper, selectEl.nextSibling);

        // Render Opsi-opsi
        function renderOptions() {
            optionsList.innerHTML = '';
            const opts = Array.from(selectEl.options);

            let hasActiveMatch = false;

            opts.forEach((opt, idx) => {
                if (opt.value === '' && opt.disabled) return;

                const optionItem = document.createElement('div');
                optionItem.className = 'custom-option-item';
                optionItem.setAttribute('data-value', opt.value);
                optionItem.setAttribute('data-index', idx);

                if (selectEl.value === opt.value) {
                    optionItem.classList.add('is-selected');
                    hasActiveMatch = true;
                }

                // Ekstrak teks utama dan sub-badge
                let mainText = opt.text;
                let subBadge = opt.dataset.badge || '';

                // Jika teks mengandung tanda kurung seperti "Futsal (Jum'at, 13:30)" atau "Ahmad (Futsal)"
                const bracketMatch = mainText.match(/^(.*?)\s*\((.*?)\)$/);
                if (bracketMatch && !subBadge) {
                    mainText = bracketMatch[1].trim();
                    subBadge = bracketMatch[2].trim();
                }

                optionItem.innerHTML = `
                    <div class="option-left-content">
                        <span class="option-main-text">${mainText}</span>
                        ${subBadge ? `<span class="option-sub-badge">${subBadge}</span>` : ''}
                    </div>
                    <i class="fas fa-check custom-option-check"></i>
                `;

                optionItem.addEventListener('click', function(e) {
                    e.stopPropagation();
                    selectValue(opt.value, opt.text);
                });

                optionsList.appendChild(optionItem);
            });

            // Update Label Trigger
            updateTriggerLabel();
        }

        // Update Label Trigger berdasarkan selectEl.value
        function updateTriggerLabel() {
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (selectedOpt && selectedOpt.value !== '') {
                let text = selectedOpt.text;
                const match = text.match(/^(.*?)\s*\(.*?\)$/);
                labelEl.textContent = match ? match[1].trim() : text;
                labelEl.classList.remove('is-placeholder');
            } else {
                labelEl.textContent = placeholder;
                labelEl.classList.add('is-placeholder');
            }

            // Sync class is-selected di daftar
            Array.from(optionsList.children).forEach(child => {
                if (child.dataset.value === selectEl.value) {
                    child.classList.add('is-selected');
                } else {
                    child.classList.remove('is-selected');
                }
            });
        }

        // Fungsi Memilih Nilai
        function selectValue(val, text) {
            if (selectEl.value !== val) {
                selectEl.value = val;
                // Dispatch native change event
                selectEl.dispatchEvent(new Event('change', { bubbles: true }));
            }
            updateTriggerLabel();
            closeDropdown();
        }

        // Buka / Tutup Dropdown
        function toggleDropdown(e) {
            if (e) e.stopPropagation();
            if (wrapper.classList.contains('is-open')) {
                closeDropdown();
            } else {
                openDropdown();
            }
        }

        function openDropdown() {
            // Tutup semua dropdown lain yang sedang terbuka di layar
            document.querySelectorAll('.custom-select-wrapper.is-open').forEach(w => {
                if (w !== wrapper) {
                    w.classList.remove('is-open');
                    w.querySelector('.custom-select-trigger')?.setAttribute('aria-expanded', 'false');
                }
            });

            wrapper.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');

            // Reset pencarian & filter jika ada
            if (searchInput) {
                searchInput.value = '';
                filterOptions('');
                setTimeout(() => searchInput.focus(), 50);
            }

            // Scroll ke item yang terpilih
            const selectedItem = optionsList.querySelector('.custom-option-item.is-selected');
            if (selectedItem) {
                setTimeout(() => {
                    selectedItem.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }, 40);
            }
        }

        function closeDropdown() {
            wrapper.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        }

        // Event Trigger Klik
        trigger.addEventListener('click', toggleDropdown);

        // Navigasi Keyboard
        trigger.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                e.preventDefault();
                openDropdown();
            } else if (e.key === 'Escape') {
                closeDropdown();
            }
        });

        // Filter Pencarian
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                filterOptions(e.target.value.toLowerCase().trim());
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDropdown();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    const firstVisible = optionsList.querySelector('.custom-option-item:not([style*="display: none"])');
                    if (firstVisible) {
                        firstVisible.click();
                    }
                }
            });

            searchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        function filterOptions(query) {
            let visibleCount = 0;
            const items = optionsList.querySelectorAll('.custom-option-item');
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (!query || text.includes(query)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            let emptyMsg = optionsList.querySelector('.custom-empty-notice');
            if (visibleCount === 0) {
                if (!emptyMsg) {
                    emptyMsg = document.createElement('div');
                    emptyMsg.className = 'custom-empty-notice';
                    emptyMsg.innerHTML = '<i class="fas fa-search-minus"></i> Tidak ditemukan pilihan yang sesuai.';
                    optionsList.appendChild(emptyMsg);
                }
                emptyMsg.style.display = 'block';
            } else if (emptyMsg) {
                emptyMsg.style.display = 'none';
            }
        }

        // Pantau jika ada perubahan eksternal pada select asli
        selectEl.addEventListener('change', updateTriggerLabel);

        // Render awal
        renderOptions();

        // Observer jika opsi selectEl berubah dinamis
        const mutationObserver = new MutationObserver(function() {
            renderOptions();
        });
        mutationObserver.observe(selectEl, { childList: true });
    };

    // Global Click Outside Listener
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.custom-select-wrapper.is-open').forEach(wrapper => {
            if (!wrapper.contains(e.target)) {
                wrapper.classList.remove('is-open');
                wrapper.querySelector('.custom-select-trigger')?.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Auto-init helper untuk elemen yang berkelas atau ber-atribut
    window.autoInitCustomSelects = function(container = document) {
        const targets = container.querySelectorAll('select.custom-select-modern, select[data-custom-select="true"]');
        targets.forEach(selectEl => {
            window.initCustomSelect(selectEl);
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => window.autoInitCustomSelects());
    } else {
        window.autoInitCustomSelects();
    }
})();
</script>
