<form action="{{ route('submitComplaints') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
    @csrf
    <div class="flex flex-row w-full space-x-6">
        <div class="space-x-6">
            @include('layouts.form.section1')
        </div>
        <div class="space-x-6">
            @include('layouts.form.section2')
        </div>
        </div>
    </div>
    <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-4">
        <button type="button" onclick="document.getElementById('contohModal').style.display='none'" class="px-6 py-3 text-slate-600 font-medium hover:text-slate-800 transition-colors">
            Batal
        </button>
        <button type="submit"
            class="px-8 py-3 bg-[#0f172a] text-white font-bold rounded-lg hover:bg-[#1e293b] focus:outline-none focus:ring-2 focus:ring-[#0f172a] focus:ring-offset-2 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                Kirim Laporan
        </button>
    </div>
</form>