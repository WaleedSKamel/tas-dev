<!-- Alertify js -->
<script src="{{ asset('assets/alertify/alertify.js') }}"></script>
<script>
    alertify.set('notifier','position', 'top-right');
    @if (session('success'))
    alertify.success("{{ session('success') }}").delay(10000);
    @elseif(session('error'))
    alertify.error("{{ session('error') }}").delay(10000);
    @elseif(session('warning'))
    alertify.warning("{{ session('warning') }}").delay(10000);
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            alertify.error("{{ $error }}");
        @endforeach
    @endif

    /*clearTimeout(window.timeout);
    alertify.success('Success', 0) && alertify.error('Error', 0)
    window.timeout = setTimeout(function(){
        alertify.dismissAll();
    },2000);*/
</script>
