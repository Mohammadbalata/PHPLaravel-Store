@if($errors->any())
<div class="alert alert-danger">
  <h2>Errors</h2>
  @foreach($errors->all() as $error)
  <li>{{$error}}</li>
  @endforeach
</div>
@endif

<div class="form-group">
  <x-form.input label="Caegory name" type="text" :value="$category->name" name="name" />

</div>
<div class="form-group">
  <label for="name">Category Parent</label>
  <select name="parent_id" class="form-control form-select">
    <option value="">Primary Category</option>
    @foreach($parents as $parent)
    <option  value="{{$parent->id}}" @selected(old('parent_id',$category->parent_id) == $parent->id) > {{$parent->name}} </option>
    @endforeach
  </select>
</div>

<div class="form-group">
  <x-form.textarea name="discription" :value="$category->discription" label="Discription" />
</div>

<div class="form-group">
  <x-form.input label='Image' type="file" value="" name="image" />
  @if($category->image)
  <img class="mt-5" height="200" src="{{asset('storage/' . $category->image)}}" />
  @endif
</div>

<div class="form-group">
<x-form.radio name="status" label="Status" :checked="$category->status" :options="['active' => 'Active', 'archived' => 'Archived']" />

</div>
<button type="submit" class="btn btn-primary">{{$button_label ?? 'Save'}}</button>