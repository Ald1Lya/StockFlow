{{-- PUSAT MODAL/POPUP APLIKASI
     AUTH: konfirmasi login/register, misalnya sukses membuat admin pertama.
     CRUD ADMIN: konfirmasi tambah, edit, atau hapus data oleh admin.
     CRUD GUDANG/KASIR & USER: gunakan komponen yang sama dari halaman/role terkait.
     Kalau desain tiap proses nanti berbeda, tambahkan varian di komponen ini;
     jangan membuat markup modal baru berulang di setiap halaman. --}}
@props([
    'show' => false,
    'title',
    'message',
    'actionUrl' => null,
    'actionText' => 'Tutup',
])

@if ($show)
    @php
        $modalId = 'modal-' . (string) \Illuminate\Support\Str::uuid();
    @endphp

    <style>
        @keyframes modal-fade-in  { from { opacity: 0 } to { opacity: 1 } }
        @keyframes modal-fade-out { from { opacity: 1 } to { opacity: 0 } }

        @keyframes modal-pop-in {
            0%   { opacity: 0; transform: translateY(18px) scale(0.94) }
            60%  { opacity: 1; transform: translateY(-2px) scale(1.005) }
            100% { opacity: 1; transform: translateY(0) scale(1) }
        }
        @keyframes modal-pop-out {
            0%   { opacity: 1; transform: translateY(0) scale(1) }
            100% { opacity: 0; transform: translateY(10px) scale(0.96) }
        }

        @keyframes modal-rise {
            from { opacity: 0; transform: translateY(8px) }
            to   { opacity: 1; transform: translateY(0) }
        }

        @keyframes modal-line-grow {
            from { transform: scaleX(0) }
            to   { transform: scaleX(1) }
        }

        @keyframes modal-glow-pulse {
            0%, 100% { opacity: 0.45 }
            50%      { opacity: 0.8 }
        }

        .modal-overlay-enter { animation: modal-fade-in 260ms ease-out both }
        .modal-overlay-exit  { animation: modal-fade-out 200ms ease-in both }
        .modal-card-enter    { animation: modal-pop-in 380ms cubic-bezier(0.22, 1, 0.36, 1) both }
        .modal-card-exit     { animation: modal-pop-out 180ms ease-in both }

        .modal-rise-1 { animation: modal-rise 400ms 80ms  cubic-bezier(0.22, 1, 0.36, 1) both }
        .modal-rise-2 { animation: modal-rise 400ms 150ms cubic-bezier(0.22, 1, 0.36, 1) both }
        .modal-rise-3 { animation: modal-rise 400ms 220ms cubic-bezier(0.22, 1, 0.36, 1) both }

        .modal-line {
            transform-origin: left;
            animation: modal-line-grow 500ms 140ms cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @media (prefers-reduced-motion: reduce) {
            .modal-overlay-enter, .modal-overlay-exit,
            .modal-card-enter, .modal-card-exit,
            .modal-rise-1, .modal-rise-2, .modal-rise-3, .modal-line {
                animation: none !important;
            }
        }
    </style>

    <div data-modal-overlay
         id="{{ $modalId }}"
         class="modal-overlay-enter fixed inset-0 z-50 flex items-center justify-center bg-[#161410]/60 p-4 backdrop-blur-sm"
         role="presentation">
        <section data-modal-card
                 class="modal-card-enter relative w-full max-w-md overflow-hidden rounded-2xl border border-[#e2dac8] bg-[#f7f4ee] p-6 shadow-2xl shadow-[#161410]/25"
                 role="dialog" aria-modal="true"
                 aria-labelledby="{{ $modalId }}-title" aria-describedby="{{ $modalId }}-message"
                 tabindex="-1">

            <!-- Hiasan: glow sudut + garis aksen atas -->
            <div class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full bg-[#c9934a]/20 blur-3xl"
                 style="animation: modal-glow-pulse 4.5s ease-in-out infinite;"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#c9934a]/60 to-transparent"></div>

            <div class="relative flex items-start justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <!-- Ikon -->
                    <span class="modal-rise-1 mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#161410] text-[#c9934a] ring-1 ring-[#161410]/10">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                        </svg>
                    </span>

                    <div class="min-w-0">
                        <h2 id="{{ $modalId }}-title"
                            class="modal-rise-1 text-xl font-semibold leading-tight text-[#161410]"
                            style="font-family:'Fraunces', ui-serif, Georgia, serif;">
                            {{ $title }}
                        </h2>
                        <!-- Divider tumbuh -->
                        <span class="modal-line mt-2 block h-px w-12 bg-[#c9934a]"></span>
                    </div>
                </div>

                <!-- Tombol close: rotate saat hover -->
                <button type="button" data-modal-close aria-label="Tutup dialog"
                        class="relative shrink-0 rounded-md px-2 py-1 text-xl leading-none text-[#7a7264]
                               transition duration-200
                               hover:rotate-90 hover:bg-[#e2dac8] hover:text-[#161410]
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c9934a]/50">
                    &times;
                </button>
            </div>

            <p id="{{ $modalId }}-message" class="modal-rise-2 mt-4 text-sm leading-relaxed text-[#7a7264]">
                {{ $message }}
            </p>

            <div class="modal-rise-3 mt-7 flex justify-end gap-2">
                @if ($actionUrl)
                    <a href="{{ $actionUrl }}"
                       class="group inline-flex items-center gap-2 rounded-lg bg-[#161410] px-4 py-2.5 text-sm font-semibold text-[#ece7dd] shadow-sm
                              transition-all duration-150
                              hover:-translate-y-0.5 hover:bg-[#2a2520] hover:shadow-md
                              active:translate-y-0 active:scale-[0.98]
                              focus:outline-none focus:ring-4 focus:ring-[#161410]/15">
                        {{ $actionText }}
                        <svg class="h-3.5 w-3.5 text-[#c9934a] transition-transform duration-150 group-hover:translate-x-0.5"
                             fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @else
                    <button type="button" data-modal-close
                            class="inline-flex items-center gap-2 rounded-lg bg-[#161410] px-4 py-2.5 text-sm font-semibold text-[#ece7dd] shadow-sm
                                   transition-all duration-150
                                   hover:-translate-y-0.5 hover:bg-[#2a2520] hover:shadow-md
                                   active:translate-y-0 active:scale-[0.98]
                                   focus:outline-none focus:ring-4 focus:ring-[#161410]/15">
                        {{ $actionText }}
                    </button>
                @endif
            </div>
        </section>
    </div>

    <script>
        (function () {
            const overlay = document.getElementById(@js($modalId));
            if (!overlay) return;

            const card   = overlay.querySelector('[data-modal-card]');
            let closing  = false;

            const onKey = (event) => {
                if (event.key === 'Escape') close();
            };

            function close() {
                if (closing) return;
                closing = true;

                overlay.classList.remove('modal-overlay-enter');
                overlay.classList.add('modal-overlay-exit');

                if (card) {
                    card.classList.remove('modal-card-enter');
                    card.classList.add('modal-card-exit');
                }

                const cleanup = () => {
                    document.removeEventListener('keydown', onKey);
                    overlay.remove();
                };

                overlay.addEventListener('animationend', cleanup, { once: true });
                setTimeout(cleanup, 400); // fallback
            }

            overlay.querySelectorAll('[data-modal-close]').forEach((btn) => {
                btn.addEventListener('click', close);
            });

            // Klik di luar card = tutup
            overlay.addEventListener('click', (event) => {
                if (event.target === overlay) close();
            });

            document.addEventListener('keydown', onKey);

            // Fokus ke dialog untuk aksesibilitas
            overlay.querySelector('[role="dialog"]')?.focus();
        })();
    </script>
@endif