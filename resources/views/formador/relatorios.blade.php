@extends('formador-layouts.apps')

@section('content')

<div class="pagetitle">
  <h1>Relatorios dos Meus Cursos</h1>
</div>

<section class="section">
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Receita estimada</h5>
          <h3>{{ number_format($receita, 2, ',', '.') }} Kz</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Desempenho por curso</h5>
      <table class="table">
        <thead>
          <tr>
            <th>Curso</th>
            <th>Status</th>
            <th>Preco</th>
            <th>Matriculas</th>
          </tr>
        </thead>
        <tbody>
          @forelse($cursos as $curso)
            <tr>
              <td>{{ $curso->titulo }}</td>
              <td>{{ ucfirst($curso->status) }}</td>
              <td>{{ number_format($curso->preco, 2, ',', '.') }} Kz</td>
              <td>{{ $curso->matriculas_count }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-muted">Ainda nao existem cursos associados a este formador.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>

@endsection
