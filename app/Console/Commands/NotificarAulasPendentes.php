<?php

namespace App\Console\Commands;

use App\Models\Matricula;
use App\Services\NotificacaoService;
use Illuminate\Console\Command;

class NotificarAulasPendentes extends Command
{
    protected $signature = 'notificacoes:aulas-pendentes';

    protected $description = 'Envia lembretes aos alunos com cursos ainda nao concluidos.';

    public function handle(NotificacaoService $notificacaoService): int
    {
        $total = 0;

        Matricula::with('user.pessoa', 'curso.aulas', 'progressos')
            ->where('progresso', '<', 100)
            ->chunk(100, function ($matriculas) use ($notificacaoService, &$total) {
                foreach ($matriculas as $matricula) {
                    $aulasConcluidas = $matricula->progressos->whereNotNull('concluido_em')->pluck('aula_id');
                    $proximaAula = $matricula->curso->aulas
                        ->whereNotIn('id', $aulasConcluidas)
                        ->sortBy('numero_aula')
                        ->first();

                    $mensagem = $proximaAula
                        ? 'Voce tem a aula '.$proximaAula->titulo.' pendente no curso '.$matricula->curso->titulo.'.'
                        : 'Voce ainda tem conteudo pendente no curso '.$matricula->curso->titulo.'.';

                    $notificacaoService->enviar(
                        $matricula->user,
                        'Aulas pendentes',
                        $mensagem,
                        ['email', 'sms', 'whatsapp']
                    );

                    $total++;
                }
            });

        $this->info($total.' lembrete(s) enviado(s).');

        return self::SUCCESS;
    }
}
