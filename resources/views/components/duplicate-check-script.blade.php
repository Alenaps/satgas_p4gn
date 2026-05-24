{{--
    Partial: Script duplikasi check
    Disisipkan di @section('scripts') tiap form.

    Variabel yang harus didefinisikan SEBELUM include partial ini:
      const DUPLICATE_CHECK_URL = '...';  // route AJAX
      const DUPLICATE_FIELD     = '...';  // 'nama_unit' | 'nama_instansi' | 'nama_jabatan'
      const DUPLICATE_INPUT_ID  = '...';  // id input yg dipantau
      const EXCLUDE_ID          = null;   // number atau null (saat edit)
      const FORM_ID             = '...';  // id <form>
--}}
<script>
(function () {
    'use strict';

    let debounceTimer  = null;
    let foundDuplicate = null;   // objek { id, name, detail } jika ada duplikat
    let userChose      = false;  // sudah memilih dari modal

    const modal          = document.getElementById('duplicate-modal');
    const modalName      = document.getElementById('modal-existing-name');
    const modalDetail    = document.getElementById('modal-existing-detail');
    const btnOverwrite   = document.getElementById('btn-overwrite');
    const btnChange      = document.getElementById('btn-change');
    const backdrop       = document.getElementById('modal-backdrop');
    const overwriteId    = document.getElementById('overwrite-id');
    const forceOverwrite = document.getElementById('force-overwrite');
    const form           = document.getElementById(FORM_ID);
    const input          = document.getElementById(DUPLICATE_INPUT_ID);

    // ── Badge peringatan di bawah input ──────────────────────
    let warningEl = null;
    function showWarning(msg) {
    removeWarning();
    warningEl = document.createElement('p');
    warningEl.className = 'mt-1.5 text-xs text-green-600 flex items-center gap-1 animate-pulse';
    warningEl.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${msg}`;
    input.parentElement.appendChild(warningEl);
    input.classList.add('border-green-400', 'bg-green-50');
    input.classList.remove('border-gray-200');
    }
    function removeWarning() {
        if (warningEl) { warningEl.remove(); warningEl = null; }
        input.classList.remove('border-green-400', 'bg-green-50');
    }

    // ── Modal ────────────────────────────────────────────────
    function openModal(data) {
        modalName.textContent   = data.name;
        modalDetail.textContent = data.detail || '';
        modal.classList.remove('hidden');
        // Trigger enter animation
        requestAnimationFrame(() => {
            modal.classList.add('modal-entering');
            requestAnimationFrame(() => {
                modal.classList.remove('modal-entering');
                modal.classList.add('modal-visible');
            });
        });
        document.body.classList.add('overflow-hidden');
    }
    function closeModal(callback) {
        modal.classList.remove('modal-visible');
        modal.classList.add('modal-leaving');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('modal-leaving');
            if (callback) callback();
        }, 220); // cocok dengan durasi transisi
        document.body.classList.remove('overflow-hidden');
    }

    // ── AJAX check ───────────────────────────────────────────
    function checkDuplicate(value) {
        if (!value || value.trim().length < 2) {
            removeWarning();
            foundDuplicate = null;
            return;
        }

        const params = new URLSearchParams({
            [DUPLICATE_FIELD]: value.trim(),
            exclude_id: EXCLUDE_ID || '',
        });

        fetch(`${DUPLICATE_CHECK_URL}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.exists) {
                foundDuplicate = data;
                showWarning(`Data "${data.name}" sudah ada — klik Simpan untuk pilih tindakan.`);
            } else {
                foundDuplicate = null;
                removeWarning();
            }
        })
        .catch(() => {});  // silent fail
    }

    // ── Listen input ─────────────────────────────────────────
    input.addEventListener('input', function () {
        userChose = false;
        overwriteId.value    = '';
        forceOverwrite.value = '';
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => checkDuplicate(this.value), 450);
    });

    // ── Intercept submit ─────────────────────────────────────
    form.addEventListener('submit', function (e) {
        if (foundDuplicate && !userChose) {
            e.preventDefault();
            openModal(foundDuplicate);
        }
    });

    // ── Tombol: Timpa ────────────────────────────────────────
    btnOverwrite.addEventListener('click', function () {
        overwriteId.value    = foundDuplicate.id;
        forceOverwrite.value = '1';
        userChose            = true;
        closeModal(() => form.submit()); // submit SETELAH animasi selesai
    });

    // ── Tombol: Ganti Nama ───────────────────────────────────
    btnChange.addEventListener('click', function () {
        closeModal(() => {
            input.focus();
            input.select();
        });
    });

    // ── Klik backdrop tutup = sama dengan "Ganti Nama" ───────
    backdrop.addEventListener('click', function () {
        closeModal(() => input.focus());
    });

})();
</script>