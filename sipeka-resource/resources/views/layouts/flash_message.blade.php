@if ($message = Session::get('success'))
<script>
  swal("{{ $message }}", "success")
</script>
@endif

@if ($message = Session::get('error'))
<script>
  swal("{{ $message }}", "error")
</script>
@endif

@if ($message = Session::get('warning'))
<script>
  swal("{{ $message }}", "warning")
</script>
@endif

@if ($message = Session::get('info'))
<script>
  swal("{{ $message }}", "info")
</script>
@endif
