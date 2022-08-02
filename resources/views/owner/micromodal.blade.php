<x-app-layout>
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('micromodal') }}
      </h2>
    </x-slot>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
             You're logged in!
            <div class="wrapper">
               <button data-micromodal-trigger="modal-1" href="javascript:;" class="modal__btn">モーダルを開く</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
          <header class="modal__header">
            <h2 class="modal__title" id="modal-1-title">
              モーダルウィンドウ
            </h2>
            <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
          </header>
          <main class="modal__content" id="modal-1-content">
            <p>
              サンプルモーダルウィンドウ情報
            </p>
          </main>
          <footer class="modal__footer">
            <button class="modal__btn modal__btn-primary">Continue</button>
            <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
          </footer>
        </div>
      </div>
    </div>
  </x-app-layout>
