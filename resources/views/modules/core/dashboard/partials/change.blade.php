{{--
    Selisih terhadap potret harian terakhir.

    $change bernilai null selama belum ada potret sama sekali; dalam keadaan itu
    tidak ditampilkan apa pun, karena "0" akan terbaca sebagai "tidak berubah"
    padahal yang benar adalah "belum ada pembandingnya".

    $goodWhenDown menandai metrik yang justru membaik saat menurun —
    keterlambatan, misalnya.
--}}
@php($goodWhenDown = $goodWhenDown ?? false)
@if(! is_null($change) && $change !== 0)
    @php($improving = $goodWhenDown ? $change < 0 : $change > 0)
    <small class="d-block mt-1 {{ $improving ? 'text-success' : 'text-warning' }}">
        <i class="bi bi-arrow-{{ $change > 0 ? 'up' : 'down' }}-short"></i>{{ $change > 0 ? '+' : '' }}{{ number_format($change) }} sejak potret terakhir
    </small>
@endif
