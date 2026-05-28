<!-- Modal Detalhes do Curso -->
<div class="modal fade" id="details-{{$curso->id}}" tabindex="-1" aria-labelledby="label-{{$curso->id}}" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      
      <!-- Cabeçalho com Gradiente Suave -->
      <div class="modal-header bg-light border-bottom-0 py-3">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                <i class="bi bi-search fs-4"></i>
            </div>
            <div>
                <h5 class="modal-title fw-bold text-dark mb-0">Visão Geral do Curso</h5>
                <small class="text-muted">Informações detalhadas e métricas</small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <div class="row g-4">
          
          <!-- COLUNA ESQUERDA: PREVIEW E FORMADOR -->
          <div class="col-xl-4">
            <!-- Card da Imagem de Capa -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="position-relative">
                    <img src="{{ $curso->foto ? Storage::url($curso->foto) : asset('assets/img/course-placeholder.jpg') }}" 
                         class="card-img-top" 
                         style="height: 200px; object-fit: cover;" 
                         alt="Capa do curso">
                    
                    <!-- Badge de Status sobre a imagem -->
                    <div class="position-absolute top-0 end-0 m-3">
                        @php
                            $statusClass = [
                                'publicado' => 'bg-success',
                                'rascunho' => 'bg-warning text-dark',
                                'inativo' => 'bg-danger'
                            ][$curso->status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }} shadow-sm px-3 py-2 text-uppercase">
                            {{ $curso->status }}
                        </span>
                    </div>
                </div>
                <div class="card-body text-center">
                    <h5 class="fw-bold mb-1">{{ $curso->titulo }}</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $curso->categoria->nome }}</span>
                </div>
            </div>

            <!-- Card do Formador -->
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-3">
                    <h6 class="fw-bold small text-uppercase text-muted mb-3">Formador Responsável</h6>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-person-badge fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 fw-bold">{{ $curso->formador->pessoa->primeironome }} {{ $curso->formador->pessoa->segundonome }}</h6>
                            <small class="text-muted">Especialista / Instrutor</small>
                        </div>
                    </div>
                </div>
            </div>
          </div>

          <!-- COLUNA DIREITA: DADOS TÉCNICOS -->
          <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                
                <!-- Navegação interna -->
                <ul class="nav nav-pills nav-justified mb-4 bg-light p-1 rounded-pill" id="courseTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold" data-bs-toggle="tab" data-bs-target="#tab-detalhes-{{$curso->id}}">Especificações</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold" data-bs-toggle="tab" data-bs-target="#tab-desc-{{$curso->id}}">Descrição Completa</button>
                  </li>
                </ul>

                <div class="tab-content" id="courseTabContent">
                  
                  <!-- Aba 1: Detalhes Técnicos -->
                  <div class="tab-pane fade show active" id="tab-detalhes-{{$curso->id}}" role="tabpanel">
                    <div class="row g-4">
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded-3 bg-white shadow-sm">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <i class="bi bi-cash-stack fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Preço do Investimento</small>
                                    <span class="fw-bold fs-5">{{ number_format($curso->preco, 2, ',', '.') }} Kz</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded-3 bg-white shadow-sm">
                                <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Carga Horária Total</small>
                                    <span class="fw-bold fs-5">{{ $curso->duracao_horas }} Horas</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted"><i class="bi bi-translate me-2"></i>Idioma Principal</span>
                                    <span class="fw-semibold">Português (Angola)</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted"><i class="bi bi-calendar-check me-2"></i>Data de Lançamento</span>
                                    <span class="fw-semibold">{{ $curso->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-bottom-0">
                                    <span class="text-muted"><i class="bi bi-shield-check me-2"></i>Certificação</span>
                                    <span class="badge bg-success bg-opacity-10 text-success">Disponível</span>
                                </div>
                            </div>
                        </div>

                    </div>
                  </div>

                  <!-- Aba 2: Descrição -->
                  <div class="tab-pane fade" id="tab-desc-{{$curso->id}}" role="tabpanel">
                    <div class="bg-light p-4 rounded-4" style="min-height: 200px;">
                        <h6 class="fw-bold mb-3">Sobre este curso</h6>
                        <p class="text-dark" style="line-height: 1.8; text-align: justify;">
                            {{ $curso->descricao ?? 'Nenhuma descrição detalhada disponível para este curso.' }}
                        </p>
                    </div>
                  </div>

                </div> <!-- End Tab Content -->
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer border-top-0 p-3 bg-light">
        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Fechar Janela</button>
        <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Imprimir Ficha
        </button>
      </div>
    </div>
  </div>
</div>

<style>
    /* Estilos Adicionais */
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    #details-{{$curso->id}} .nav-pills .nav-link {
        color: #6c757d;
        transition: all 0.3s;
    }

    #details-{{$curso->id}} .nav-pills .nav-link.active {
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }

    #details-{{$curso->id}} .modal-content {
        border-radius: 20px;
    }
</style>