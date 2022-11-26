<div class="form-group">
  <label class="control-label">Title</label>
  <input type="text" name="title" data-shortcode-attribute="title" class="form-control" placeholder="Title">
</div>
<div class="form-group">
  <label class="control-label">{{ __('Select a Category') }}</label>
  <select name="category" class="form-control" data-shortcode-attribute="category">
    @foreach ($categories as $category)
      <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
  </select>
</div>
