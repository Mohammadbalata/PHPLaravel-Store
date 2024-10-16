@props([
'type' => 'text', 'value' => '' ,'name' => '','label' => false
])
@if($label)
<label for="{{$name}}">{{$label}}</label>
@endif
<input type="{{$type}}" name="{{$name}}" id="{{$name}}" value="{{old($name,$value)}}" {{$attributes->class(['form-control','is-invalid' => $errors->has($name)])}}>
@error($name)
<div class="invalid-feedback">
    {{$errors->first($name)}}
</div>
@enderror