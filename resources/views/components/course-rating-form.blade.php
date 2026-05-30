@php
    $notaActual = old('nota', $minhaAvaliacao->nota ?? null);
    $notaSelecionada = $notaActual !== null ? (int) $notaActual : null;
    $prefixoAvaliacao = 'curso-rating-'.$curso->id;
@endphp

<form action="{{ route('avaliacoes.store', $curso) }}" method="POST" class="review-form course-rating-form">
    @csrf
    <h3>{{ $minhaAvaliacao ? 'Actualizar avaliacao' : 'Avaliar este curso' }}</h3>

    <fieldset class="course-rating-field">
        <legend>Nota do curso</legend>
        <label class="rating-zero" for="{{ $prefixoAvaliacao }}-0">
            <input
                type="radio"
                id="{{ $prefixoAvaliacao }}-0"
                name="nota"
                value="0"
                @checked($notaSelecionada === 0)
                required
            >
            <span>0</span>
        </label>

        <div class="star-rating" aria-label="Nota de 1 a 5 estrelas">
            @for($i = 5; $i >= 1; $i--)
                <input
                    type="radio"
                    id="{{ $prefixoAvaliacao }}-{{ $i }}"
                    name="nota"
                    value="{{ $i }}"
                    @checked($notaSelecionada === $i)
                    required
                >
                <label for="{{ $prefixoAvaliacao }}-{{ $i }}" title="{{ $i }} de 5">
                    <i class="bi bi-star-fill"></i>
                </label>
            @endfor
        </div>
    </fieldset>

    @error('nota')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    <textarea name="comentario" rows="4" placeholder="Escreva o seu comentario sobre o curso" required>{{ old('comentario', $minhaAvaliacao->comentario ?? '') }}</textarea>
    @error('comentario')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    <button type="submit" class="course-review-submit">
        {{ $minhaAvaliacao ? 'Actualizar comentario' : 'Enviar comentario' }}
    </button>
</form>
