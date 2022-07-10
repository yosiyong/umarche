<x-tests.app>
    <x-slot name="header">
        ヘッダー１
    </x-slot>
component-test1です。
<x-tests.card title="タイトル1" content="本文" :message="$message" />
<x-tests.card title="タイトル2" />
</x-tests.app>
