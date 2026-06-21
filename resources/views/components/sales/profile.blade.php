<div class="space-y-5 max-w-3xl mx-auto">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex items-center gap-4">
        <div
          class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center shadow-lg shadow-orange-500/25">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Profile Saya</h1>
          <p class="text-sm text-gray-400 mt-0.5">Kelola data diri & dokumen pendukung</p>
        </div>
      </div>
    </div>
  </div>

  {{-- FORM --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 space-y-5">

      {{-- DATA DIRI --}}
      <div>
        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5" />
          </svg>
          Informasi Pribadi
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
            <input type="text" wire:model="nama"
              class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-medium bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email</label>
            <input type="email" wire:model="email"
              class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-medium bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">No. HP</label>
            <input type="text" wire:model="no_telp"
              class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-medium bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">NIK</label>
            <input type="text" wire:model="nik"
              class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-medium bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alamat</label>
            <textarea wire:model="alamat" rows="3"
              class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-medium bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none resize-none"></textarea>
          </div>
        </div>
      </div>

      <hr class="border-gray-100">

      {{-- DOKUMEN --}}
      <div>
        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Dokumen Pendukung
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {{-- FOTO KTP --}}
          <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Foto KTP</label>
            @if ($existing_ktp)
              <a href="{{ Storage::url($existing_ktp) }}" target="_blank"
                class="inline-flex items-center gap-2 text-blue-600 text-sm font-semibold hover:text-blue-700 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat KTP
              </a>
            @else
              <p class="text-xs text-gray-400 mb-3">Belum diupload</p>
            @endif
            <input type="file" wire:model="foto_ktp"
              class="text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
          </div>

          {{-- SURAT KERJA --}}
          <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Surat Kerja</label>
            @if ($existing_surat)
              <a href="{{ Storage::url($existing_surat) }}" target="_blank"
                class="inline-flex items-center gap-2 text-blue-600 text-sm font-semibold hover:text-blue-700 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Lihat Surat Kerja
              </a>
            @else
              <p class="text-xs text-gray-400 mb-3">Belum diupload</p>
            @endif
            <input type="file" wire:model="surat_kerja"
              class="text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
          </div>

          {{-- FOTO PROFIL --}}
          <div class="sm:col-span-2 bg-gray-50 rounded-2xl p-5 border border-gray-200">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Foto Profil</label>
            <div class="flex items-center gap-4">
              @if ($existing_foto)
                <img src="{{ Storage::url($existing_foto) }}"
                  class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow-md">
              @else
                <div class="w-16 h-16 rounded-2xl bg-gray-200 flex items-center justify-center">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
              @endif
              <input type="file" wire:model="foto_profil"
                class="text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>
          </div>
        </div>
      </div>

      {{-- SAVE BUTTON --}}
      <div class="pt-4 border-t border-gray-100">
        <button wire:click="updateProfile" wire:loading.class="opacity-50" wire:target="updateProfile"
          class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white px-8 py-3 rounded-xl text-sm font-bold transition shadow-lg shadow-orange-500/25">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
          </svg>
          Simpan Profile
        </button>
      </div>

    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    Livewire.on('dataSaved', (e) => {
      Swal.fire({
        title: e.title || 'Berhasil!',
        text: e.message || 'Data berhasil disimpan.',
        icon: e.type || 'success',
        confirmButtonColor: '#F97316',
        customClass: {
          popup: 'rounded-2xl',
          confirmButton: 'rounded-xl text-sm font-bold px-5'
        },
      });
    });
  });
</script>
