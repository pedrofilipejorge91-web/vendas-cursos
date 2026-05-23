{{-- ALERT DE SUCESSO --}}
@if (session('success'))
    <div id="alert-success" class="alert alert-success">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if (alert) alert.remove();
        }, 3000);
    </script>
@endif


{{-- ALERT DE ERRO --}}
@if ($errors->any())
    <div id="alert-error" class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <script>
        setTimeout(() => {
            const alert = document.getElementById('alert-error');
            if (alert) alert.remove();
        }, 4000);
    </script>
@endif
