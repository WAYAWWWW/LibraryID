
@section('content')
<h2>Edit Buku</h2>
<form method="POST" action="{{ route('books.update', $book) }}" class="col-md-6">
  @csrf
  @method('PUT')
  <div class="mb-3"><label>Judul</label><input name="title" class="form-control" value="{{ $book->title }}" required></div>
  <div class="mb-3"><label>Penulis</label><input name="author" class="form-control" value="{{ $book->author }}"></div>
  <div class="mb-3"><label>Penerbit</label><input name="publisher" class="form-control" value="{{ $book->publisher }}"></div>
  <div class="mb-3"><label>Tahun Terbit</label><input type="number" name="year" class="form-control" value="{{ $book->year }}" min="1900" max="2100"></div>
  <div class="mb-3"><label>Sinopsis</label><textarea name="synopsis" class="form-control" rows="4">{{ $book->synopsis }}</textarea></div>
  <div class="mb-3"><label>Stok</label><input type="number" name="stock" class="form-control" value="{{ $book->stock }}" required min="0"></div>
  <button class="btn btn-primary">Update</button>
  <a href="{{ route('books.show', $book) }}" class="btn btn-secondary">Batal</a>
</form>
