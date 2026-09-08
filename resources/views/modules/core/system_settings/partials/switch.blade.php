{{--
    Satu saklar aturan operasional.

    Input hidden bernilai "0" mendahului checkbox: peramban tidak mengirim
    checkbox yang tidak dicentang, sehingga tanpa hidden ini sebuah saklar yang
    sudah menyala mustahil dimatikan lewat formulir.
--}}
@php($default = $default ?? true)
<div class="form-check form-switch mb-3">
    <input type="hidden" name="{{ $key }}" value="0">
    <input class="form-check-input" type="checkbox" role="switch" id="{{ $key }}" name="{{ $key }}" value="1"
           @checked(filter_var(old($key, $settings[$key] ?? $default), FILTER_VALIDATE_BOOLEAN))>
    <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
    <div class="form-text">{{ $help }}</div>
</div>
