@if ($message = Session::get('success'))
<script>
  toastr.success("{{ $message }}", "Berhasil")
</script>
@endif

@if ($message = Session::get('error'))
<script>
  toastr.error("{{ $message }}", "Gagal")
</script>
@endif

@if ($message = Session::get('warning'))
<script>
  toastr.error("{{ $message }}", "Peringatan")
</script>
@endif

@if ($message = Session::get('info'))
<script>
  toastr.success("{{ $message }}", "Informasi")
</script>
@endif
