@extends('welcome.apps')

@section('content')

<section class="min-h-screen bg-slate-100 py-24 px-6 md:px-12">
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-xl p-8">

    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 border-b pb-6 mb-6">
        <div>
            <p class="text-sm uppercase tracking-widest text-blue-700 font-bold">Comprovante de pagamento</p>
            <h1 class="text-3xl font-black text-slate-900 mt-2">Pedido {{ $pedido->referencia }}</h1>
            <p class="text-slate-500 mt-1">Centro de Formacao Paruana Comercial</p>
        </div>
        <div class="text-left md:text-right">
            <span class="inline-flex px-4 py-2 rounded-full text-sm font-bold {{ $pedido->status === 'pago' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ ucfirst($pedido->status) }}
            </span>
            <p class="text-sm text-slate-500 mt-2">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="border rounded-lg p-5">
            <h2 class="font-bold text-slate-800 mb-3">Dados do aluno</h2>
            <p>{{ $pedido->user->name }}</p>
            <p class="text-slate-500">{{ $pedido->user->email }}</p>
        </div>
        <div class="border rounded-lg p-5">
            <h2 class="font-bold text-slate-800 mb-3">Referencia de pagamento</h2>
            <p class="text-2xl font-black text-blue-700">{{ $pedido->pagamento->referencia }}</p>
            <p class="text-slate-500">{{ str_replace('_', ' ', ucfirst($pedido->pagamento->metodo)) }}</p>
            @if($pedido->pagamento->comprovativo)
                <a class="text-sm text-blue-700 font-bold mt-2 inline-block" href="{{ Storage::url($pedido->pagamento->comprovativo) }}" target="_blank">Ver comprovativo enviado</a>
            @endif
            @if($pedido->expira_em)
                <p class="text-sm text-red-600 mt-2">Confirmar ate {{ $pedido->expira_em->format('d/m/Y H:i') }}</p>
            @endif
        </div>
    </div>

    <div class="border rounded-lg overflow-hidden mb-8">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-sm">
                <tr>
                    <th class="p-4">Curso</th>
                    <th class="p-4 text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->itens as $item)
                    <tr class="border-t">
                        <td class="p-4 font-semibold">{{ $item->titulo }}</td>
                        <td class="p-4 text-right">{{ number_format($item->preco, 2, ',', '.') }} Kz</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-between text-slate-600">
        <span>Subtotal</span>
        <span>{{ number_format($pedido->subtotal, 2, ',', '.') }} Kz</span>
    </div>
    <div class="flex justify-between text-green-700 mt-2">
        <span>Desconto</span>
        <span>-{{ number_format($pedido->desconto, 2, ',', '.') }} Kz</span>
    </div>
    <div class="flex justify-between text-2xl font-black text-slate-900 mt-4 pt-4 border-t">
        <span>Total</span>
        <span>{{ number_format($pedido->total, 2, ',', '.') }} Kz</span>
    </div>

@if($gatewayDescription)
            <div class="mb-6 p-5 bg-blue-50 border border-blue-100 rounded-lg text-blue-900">
                <p class="font-semibold">Instruções de pagamento:</p>
                <p>{{ $gatewayDescription }}</p>
                @if($gatewayUrl)
                    <a href="{{ $gatewayUrl }}" target="_blank" class="inline-flex items-center justify-center mt-3 px-5 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition">
                        Ir para o Pagamento
                    </a>
                @endif
            </div>
        @endif

        <div class="mt-8 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold text-center hover:bg-blue-700 transition">
            Ir para meus cursos
        </a>
        <a href="{{ url('/') }}" class="border border-slate-300 px-6 py-3 rounded-lg font-bold text-center hover:bg-slate-50 transition">
            Voltar ao catalogo
        </a>
    </div>

</div>
</section>

@endsection
