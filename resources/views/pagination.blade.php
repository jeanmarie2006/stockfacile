@if ($paginator->hasPages())
<nav class="no-print mt-5 flex items-center justify-between gap-3" aria-label="Pagination">
  @if ($paginator->onFirstPage()) <span class="btn-ghost btn-sm opacity-40">← Précédent</span> @else <a class="btn-ghost btn-sm" href="{{ $paginator->previousPageUrl() }}">← Précédent</a> @endif
  <span class="text-sm font-semibold text-slate-500">Page {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
  @if ($paginator->hasMorePages()) <a class="btn-ghost btn-sm" href="{{ $paginator->nextPageUrl() }}">Suivant →</a> @else <span class="btn-ghost btn-sm opacity-40">Suivant →</span> @endif
</nav>
@endif
