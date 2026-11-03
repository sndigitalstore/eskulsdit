@php
    $themeColor = ($theme ?? 'blue') === 'red' ? '#ef4444' : (($theme ?? 'blue') === 'green' ? '#10b981' : '#0284c7');
    $themeLightBg = ($theme ?? 'blue') === 'red' ? '#fef2f2' : (($theme ?? 'blue') === 'green' ? '#f0fdf4' : '#f0f9ff');
    $themeBorder = ($theme ?? 'blue') === 'red' ? '#fca5a5' : (($theme ?? 'blue') === 'green' ? '#86efac' : '#bae6fd');
    $themeText = ($theme ?? 'blue') === 'red' ? '#991b1b' : (($theme ?? 'blue') === 'green' ? '#166534' : '#0369a1');

    $studentItems = $students->map(function($st) {
        return [
            'id' => (int) $st->id,
            'name' => (string) $st->name,
            'class' => (string) ($st->class ?? ''),
            'nis' => (string) ($st->nis ?? '-'),
        ];
    })->values();

    $initialId = old('student_id', $selectedStudent->id ?? null);
    $initialStudent = null;
    if ($initialId) {
        $found = $students->firstWhere('id', $initialId);
        if ($found) {
            $initialStudent = [
                'id' => (int) $found->id,
                'name' => (string) $found->name,
                'class' => (string) ($found->class ?? ''),
                'nis' => (string) ($found->nis ?? '-'),
            ];
        }
    }
@endphp

<div class="student-search-picker-component" style="position: relative;">
    <label style="display: block; margin-bottom: 6px; font-weight: 700; color: #334155; font-size: 0.88rem;">
        Cari & Pilih Nama Siswa <span style="color: #ef4444;">*</span>
    </label>

    {{-- Input hidden asli untuk submit form Laravel --}}
    <input type="hidden" name="student_id" id="student_id" value="{{ $initialStudent['id'] ?? '' }}" required>

    {{-- KOTAK SISWA TERPILIH --}}
    <div id="ssp_selected_card" style="display: {{ $initialStudent ? 'flex' : 'none' }}; align-items: center; justify-content: space-between; background: {{ $themeLightBg }}; border: 1.5px solid {{ $themeBorder }}; border-radius: 12px; padding: 10px 14px; gap: 12px; transition: all 0.2s ease;">
        <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: #ffffff; color: {{ $themeColor }}; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <i class="fas fa-user-check"></i>
            </div>
            <div style="min-width: 0;">
                <div id="ssp_selected_name" style="font-weight: 800; color: {{ $themeText }}; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $initialStudent['name'] ?? '' }}
                </div>
                <div style="font-size: 0.78rem; color: #64748b; display: flex; gap: 8px; align-items: center; margin-top: 2px;">
                    <span id="ssp_selected_class" style="background: #ffffff; color: #334155; border: 1px solid #e2e8f0; padding: 1px 7px; border-radius: 4px; font-weight: 700;">
                        {{ $initialStudent ? 'Kelas ' . $initialStudent['class'] : '' }}
                    </span>
                    <span id="ssp_selected_nis" style="color: #64748b;">
                        {{ $initialStudent ? 'NIS: ' . $initialStudent['nis'] : '' }}
                    </span>
                </div>
            </div>
        </div>
        <button type="button" id="ssp_btn_change" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #475569; padding: 7px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; flex-shrink: 0; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <i class="fas fa-search" style="font-size: 0.75rem;"></i> Ganti Siswa
        </button>
    </div>

    {{-- KOTAK INPUT PENCARIAN & HASIL --}}
    <div id="ssp_search_container" style="display: {{ $initialStudent ? 'none' : 'block' }}; position: relative;">
        <div style="position: relative; display: flex; align-items: center;">
            <i class="fas fa-search" style="position: absolute; left: 14px; color: #94a3b8; font-size: 0.95rem; pointer-events: none;"></i>
            <input type="text" id="ssp_search_input" class="form-control" 
                   placeholder="Ketik nama siswa, kelas (cth: 1A), atau NIS..." 
                   autocomplete="off"
                   style="padding-left: 40px; padding-right: 38px; height: 46px; border-radius: 10px; font-size: 0.92rem; border: 1.5px solid #cbd5e1; background: #ffffff;">
            <button type="button" id="ssp_btn_clear_text" style="display: none; position: absolute; right: 10px; background: none; border: none; color: #94a3b8; font-size: 1rem; cursor: pointer; padding: 6px;" title="Bersihkan">
                <i class="fas fa-times-circle"></i>
            </button>
        </div>

        {{-- Dropdown Hasil Pencarian --}}
        <div id="ssp_results_dropdown" style="display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1050; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 12px; box-shadow: 0 12px 28px rgba(0,0,0,0.12); max-height: 280px; overflow-y: auto;">
        </div>

        <div id="ssp_helper_text" style="font-size: 0.76rem; color: #64748b; margin-top: 4px; display: flex; justify-content: space-between;">
            <span><i class="fas fa-info-circle"></i> Ketik nama siswa untuk mencari (Total: {{ $students->count() }} siswa)</span>
            <span id="ssp_match_count" style="font-weight: 700; color: {{ $themeColor }};"></span>
        </div>
    </div>
</div>

<style>
    .ssp-item {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.15s ease;
    }
    .ssp-item:last-child {
        border-bottom: none;
    }
    .ssp-item:hover, .ssp-item.ssp-active {
        background: #f8fafc;
    }
    .ssp-item-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.9rem;
    }
    .ssp-item-meta {
        font-size: 0.76rem;
        color: #64748b;
        margin-top: 2px;
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .ssp-item-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 1px 6px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 0.72rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sspStudents = @json($studentItems);
    const hiddenInput = document.getElementById('student_id');
    const searchContainer = document.getElementById('ssp_search_container');
    const searchInput = document.getElementById('ssp_search_input');
    const clearBtn = document.getElementById('ssp_btn_clear_text');
    const resultsBox = document.getElementById('ssp_results_dropdown');
    const matchCount = document.getElementById('ssp_match_count');
    const selectedCard = document.getElementById('ssp_selected_card');
    const selectedName = document.getElementById('ssp_selected_name');
    const selectedClass = document.getElementById('ssp_selected_class');
    const selectedNis = document.getElementById('ssp_selected_nis');
    const btnChange = document.getElementById('ssp_btn_change');

    let activeIndex = -1;

    function renderResults(query) {
        const q = (query || '').toLowerCase().trim();
        let matches = [];

        if (q === '') {
            matches = sspStudents.slice(0, 15);
        } else {
            matches = sspStudents.filter(s => {
                const nameMatch = s.name.toLowerCase().includes(q);
                const classMatch = s.class.toLowerCase().includes(q);
                const nisMatch = s.nis.toLowerCase().includes(q);
                return nameMatch || classMatch || nisMatch;
            });
        }

        activeIndex = -1;
        resultsBox.innerHTML = '';

        if (matches.length === 0) {
            resultsBox.innerHTML = `
                <div style="padding: 20px 14px; text-align: center; color: #94a3b8; font-size: 0.86rem;">
                    <i class="fas fa-search" style="font-size: 1.5rem; margin-bottom: 6px; display: block; color: #cbd5e1;"></i>
                    Tidak ditemukan siswa dengan kata kunci "<strong>${escapeHtml(query)}</strong>".
                </div>
            `;
            matchCount.textContent = '0 ditemukan';
            resultsBox.style.display = 'block';
            return;
        }

        if (q === '') {
            matchCount.textContent = 'Menampilkan 15 siswa pertama';
        } else {
            matchCount.textContent = matches.length + ' ditemukan';
        }

        const maxDisplay = 25;
        const displayList = matches.slice(0, maxDisplay);

        displayList.forEach((st, idx) => {
            const div = document.createElement('div');
            div.className = 'ssp-item';
            div.setAttribute('data-index', idx);
            div.innerHTML = `
                <div style="min-width: 0;">
                    <div class="ssp-item-name">${escapeHtml(st.name)}</div>
                    <div class="ssp-item-meta">
                        <span class="ssp-item-badge">Kelas ${escapeHtml(st.class)}</span>
                        <span>• NIS: ${escapeHtml(st.nis)}</span>
                    </div>
                </div>
                <div style="color: #cbd5e1; font-size: 0.8rem; flex-shrink: 0;">
                    <i class="fas fa-check-circle" style="display: none;"></i>
                    <i class="fas fa-chevron-right"></i>
                </div>
            `;

            div.addEventListener('click', function() {
                selectStudent(st);
            });

            resultsBox.appendChild(div);
        });

        if (matches.length > maxDisplay) {
            const footer = document.createElement('div');
            footer.style.padding = '8px 12px';
            footer.style.background = '#f8fafc';
            footer.style.color = '#64748b';
            footer.style.fontSize = '0.75rem';
            footer.style.textAlign = 'center';
            footer.style.borderTop = '1px solid #f1f5f9';
            footer.innerHTML = `<em>Ketik lebih spesifik untuk melihat ${matches.length - maxDisplay} siswa lainnya...</em>`;
            resultsBox.appendChild(footer);
        }

        resultsBox.style.display = 'block';
    }

    function selectStudent(st) {
        hiddenInput.value = st.id;
        selectedName.textContent = st.name;
        selectedClass.textContent = 'Kelas ' + (st.class || '-');
        selectedNis.textContent = 'NIS: ' + (st.nis || '-');

        resultsBox.style.display = 'none';
        searchContainer.style.display = 'none';
        selectedCard.style.display = 'flex';
        searchInput.style.borderColor = '#cbd5e1';
    }

    function clearSelection() {
        hiddenInput.value = '';
        selectedCard.style.display = 'none';
        searchContainer.style.display = 'block';
        searchInput.value = '';
        clearBtn.style.display = 'none';
        searchInput.focus();
        renderResults('');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Events
    searchInput.addEventListener('input', function() {
        const val = this.value;
        clearBtn.style.display = val.length > 0 ? 'block' : 'none';
        renderResults(val);
    });

    searchInput.addEventListener('focus', function() {
        renderResults(this.value);
    });

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        this.style.display = 'none';
        searchInput.focus();
        renderResults('');
    });

    btnChange.addEventListener('click', function() {
        clearSelection();
    });

    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = resultsBox.querySelectorAll('.ssp-item');
        if (!items || items.length === 0 || resultsBox.style.display === 'none') return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = (activeIndex + 1) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIndex >= 0 && items[activeIndex]) {
                items[activeIndex].click();
            } else if (items.length > 0) {
                items[0].click();
            }
        } else if (e.key === 'Escape') {
            resultsBox.style.display = 'none';
        }
    });

    function updateActiveItem(items) {
        items.forEach((it, idx) => {
            if (idx === activeIndex) {
                it.classList.add('ssp-active');
                it.scrollIntoView({ block: 'nearest' });
            } else {
                it.classList.remove('ssp-active');
            }
        });
    }

    // Click outside to close results
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.student-search-picker-component')) {
            resultsBox.style.display = 'none';
        }
    });

    // Form submit validation safeguard
    const form = searchContainer.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!hiddenInput.value) {
                e.preventDefault();
                searchContainer.style.display = 'block';
                selectedCard.style.display = 'none';
                searchInput.style.borderColor = '#ef4444';
                searchInput.focus();
                renderResults(searchInput.value);
                alert('Silakan cari dan pilih salah satu nama siswa terlebih dahulu.');
            }
        });
    }
});
</script>
