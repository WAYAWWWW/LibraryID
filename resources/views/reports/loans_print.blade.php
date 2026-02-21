<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Peminjaman</title>
  
}    <link rel="stylesheet" href="{{ asset('css/reports-loans_print.css') }}">
</head>
<body>
  <h2>Laporan Peminjaman</h2>
  <table>
    <thead>
      <tr><th>Nama</th><th>Email</th><th>Nama Buku</th><th>Pengarang</th><th>Tahun</th><th>Tanggal Peminjaman</th><th>Tanggal Pengembalian</th><th>Status</th></tr>
    </thead>
    <tbody>
      @foreach($loans as $loan)
        <tr>
          <td>{{ $loan->user->name }}</td>
          <td>{{ $loan->user->email }}</td>
          <td>{{ $loan->book->title }}</td>
          <td>{{ $loan->book->author }}</td>
          <td>{{ $loan->book->year }}</td>
          <td>{{ $loan->borrow_date }}</td>
          <td>{{ $loan->due_date }}</td>
          <td>{{ $loan->status }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
