# TODO - UI/UX (Mobile-first)

## Objetivo
Reestruturar o visual da plataforma para um design clean/moderno, mobile-first, mantendo flexibilidade para desktop no dashboard do aluno.

## Checklist (passo a passo)
- [x] (1) Atualizar design system/cores em `public/assets/css/welcome.css` (introduzir laranja, classes auxiliares, badge carrinho).
- [x] (2) Reestruturar `resources/views/welcome/header.blade.php` para conter: logo, pesquisa (RF-07), filtros rápidos/funil (RF-05), botões Entrar/Cadastrar, carrinho com badge (RF-12) — **mobile-first** com rotas funcionais e fallback seguro.
- [x] (2.1) Garantir `cartCount` (badge do carrinho) quando existir e fallback seguro quando não existir.
- [ ] (3) Atualizar `resources/views/home/welcome.blade.php`: Hero “O Promo”, Prova Social com ícones, seção “Cursos em Alta/Novidades” com abas, cards com preço/duração/instrutor e estrelas.
- [ ] (4) Ajustar `resources/views/home/catalogo.blade.php` para refletir abas rápidas e cards com estrelas/botões.
- [ ] (5) Atualizar `resources/views/welcome/footer.blade.php` com links (Quem Somos, Termos, Privacidade, Contactos), meios de pagamento e selo de segurança.
- [ ] (6) Dashboard aluno (responsivo mobile-first e flexível desktop): refatorar `resources/views/estudante/dashboard.blade.php` para empilhar no mobile mantendo grade/cards e legibilidade no desktop; incluir saudação, continuar onde parou, progresso, retomar aula, lembrete carrinho, destaque certificado pronto e menu rápido.
- [ ] (7) Ajustar `resources/views/estudant-layouts/header.blade.php` e `resources/views/estudant-layouts/aside.blade.php` para não conflitar com o novo layout (melhor compatibilidade com mobile).
- [ ] (8) (Opcional) Harmonizar `resources/views/estudante/cursos.blade.php` com o novo estilo.
- [ ] (9) Testes manuais: homepage, catálogo, detalhe curso, dashboard, notificações/carrinho.

