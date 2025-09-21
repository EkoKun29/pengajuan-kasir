@extends('layouts.app')
@section('title', 'Data Karyawan')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css"/>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">DATA PLOT</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola Plot</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.database.plot.sync') }}" class="bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2"> <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/> </svg> Update Data </a>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-4">
        <div class="overflow-x-auto">
            <table id="table-plot" class="display nowrap stripe hover w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Plot</th>
                        <th>Sub Plot</th>
                        <th>Akun Keuangan</th>
                        <th>Keperluan Beban</th>
                        <th>Keterangan Pajak</th>
                        <th>Jenis Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akunPlot as $ap)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ap->plot }}</td>
                            <td>{{ $ap->sub_plot }}</td>
                            <td>{{ $ap->akun_keuangan }}</td>
                            <td>{{ $ap->keperluan_beban }}</td>
                            <td>{{ $ap->keterangan_pajak }}</td>
                            <td>{{ $ap->jenis_transaksi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#table-plot').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "›",
                        previous: "‹"
                    }
                }
            });
        });
    </script>
@endpush
