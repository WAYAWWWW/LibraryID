
@section('content')
  <h3>Peminjaman Anda Disetujui</h3>
  <p>Halo {{ $loan->user->name }},</p>
  <p>Peminjaman buku <strong>{{ $loan->book->title }}</strong> telah disetujui. Tenggat pengembalian: {{ $loan->due_date }}.</p>
  <p>Barcode: {{ $loan->barcode }}</p>

