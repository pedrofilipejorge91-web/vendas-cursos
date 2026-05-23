<div class="modal fade" id="details-{{$aula->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Detalhes da Aula: {{ $aula->titulo }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-body p-0">
                                @if($aula->tipo == 'video' && $aula->url_conteudo)
                                    <div class="ratio ratio-16x9 bg-black shadow-sm rounded">
                                        @if(str_contains($aula->url_conteudo, 'youtube.com') || str_contains($aula->url_conteudo, 'youtu.be'))
                                            @php
                                                // Converte link comum em link de embed para o YouTube
                                                $url = $aula->url_conteudo;
                                                $videoId = "";
                                                if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
                                                    $videoId = $match[1];
                                                }
                                            @endphp
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
                                        @else
                                            <video controls controlsList="nodownload">
                                                <source src="{{ $aula->url_conteudo }}" type="video/mp4">
                                                Seu navegador não suporta a reprodução de vídeos.
                                            </video>
                                        @endif
                                    </div>
                                @elseif($aula->tipo == 'pdf')
                                    <div class="text-center p-5">
                                        <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 4rem;"></i>
                                        <p class="mt-2">Esta aula contém um material em PDF.</p>
                                        <a href="{{ $aula->url_conteudo }}" target="_blank" class="btn btn-outline-danger">Abrir Documento</a>
                                    </div>
                                @else
                                    <div class="text-center p-5 bg-light">
                                        <i class="bi bi-journal-text text-primary" style="font-size: 4rem;"></i>
                                        <p class="mt-2">Conteúdo de texto ou quiz.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5">
                        <div class="card h-100">
                            <div class="card-body pt-3">
                                <ul class="nav nav-tabs nav-tabs-bordered">
                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview-{{$aula->id}}">Visão Geral</button>
                                    </li>
                                </ul>

                                <div class="tab-content pt-2">
                                    <div class="tab-pane fade show active profile-overview" id="overview-{{$aula->id}}">
                                        
                                        <h5 class="card-title">Descrição</h5>
                                        <p class="small fst-italic">{{ $aula->descricao ?? 'Nenhuma descrição fornecida.' }}</p>

                                        <h5 class="card-title">Dados da Aula</h5>

                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-md-4 label text-muted">Título</div>
                                            <div class="col-lg-8 col-md-8 fw-bold">{{ $aula->titulo }}</div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-md-4 label text-muted">Curso</div>
                                            <div class="col-lg-8 col-md-8">{{ $aula->curso->titulo ?? 'N/A' }}</div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-md-4 label text-muted">Tipo</div>
                                            <div class="col-lg-8 col-md-8">
                                                <span class="badge bg-info text-dark">{{ strtoupper($aula->tipo) }}</span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-md-4 label text-muted">Nº da Aula</div>
                                            <div class="col-lg-8 col-md-8">{{ $aula->numero_aula }}</div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-md-4 label text-muted">Duração</div>
                                            <div class="col-lg-8 col-md-8">{{ $aula->duracao_minutos }} minutos</div>
                                        </div>

                                        @if($aula->url_conteudo)
                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-md-4 label text-muted">Link Direto</div>
                                            <div class="col-lg-8 col-md-8">
                                                <a href="{{ $aula->url_conteudo }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 200px;">
                                                    Ver conteúdo original
                                                </a>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>