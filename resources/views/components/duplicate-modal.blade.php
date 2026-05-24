{{--
    Komponen: Modal peringatan data duplikat
    Props:
      $entity   : 'unit' | 'instansi' | 'jabatan'
      $checkUrl : route untuk AJAX check (e.g. route('admin.unit.check-duplicate'))
      $field    : nama field yang dicek (e.g. 'nama_unit')
      $inputId  : id dari <input> yang dipantau
      $excludeId: (opsional) id record saat edit — untuk mengecualikan dirinya sendiri
--}}

<style>
    #duplicate-modal .modal-panel {
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
        transform: scale(0.95) translateY(8px);
        opacity: 0;
    }
    #duplicate-modal.modal-entering .modal-panel,
    #duplicate-modal.modal-visible .modal-panel {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
    #duplicate-modal.modal-leaving .modal-panel {
        transform: scale(0.95) translateY(8px);
        opacity: 0;
    }
    #duplicate-modal .modal-backdrop-el {
        transition: opacity 0.2s ease;
        opacity: 0;
    }
    #duplicate-modal.modal-entering .modal-backdrop-el,
    #duplicate-modal.modal-visible .modal-backdrop-el {
        opacity: 1;
    }
    #duplicate-modal.modal-leaving .modal-backdrop-el {
        opacity: 0;
    }
</style>

{{-- Modal overlay --}}
<div id="duplicate-modal"
     class="fixed inset-0 z-50 flex items-center justify-center hidden"
     role="dialog" aria-modal="true">

    {{-- Backdrop --}}
    <div class="modal-backdrop-el absolute inset-0 bg-black/40 backdrop-blur-sm" id="modal-backdrop"></div>

    {{-- Panel --}}
    <div class="modal-panel relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center gap-3 bg-green-50 border-b border-green-200 px-6 py-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-green-500 text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 text-base">Data Sudah Ada</h3>
                <p class="text-xs text-gray-500 mt-0.5">Ditemukan data dengan nama yang sama</p>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600 mb-1">Data yang sudah tersimpan:</p>
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mb-4">
                <p class="font-semibold text-gray-800 text-sm" id="modal-existing-name">-</p>
                <p class="text-xs text-gray-500 mt-0.5" id="modal-existing-detail"></p>
            </div>
            <p class="text-sm text-gray-600">Apa yang ingin Anda lakukan?</p>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-2 px-6 pb-6">
            <button id="btn-overwrite"
                    class="flex-1 inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                <i class="fas fa-pen-to-square"></i> Timpa Data
            </button>
            <button id="btn-change"
                    class="flex-1 inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                <i class="fas fa-rotate-left"></i> Ganti Nama
            </button>
        </div>

    </div>
</div>

{{-- Hidden field untuk sinyal "timpa" --}}
<input type="hidden" name="overwrite_id" id="overwrite-id" value="">
<input type="hidden" name="force_overwrite" id="force-overwrite" value="">