@php $m = $value ? \App\Models\Media::find($value) : null; @endphp
<div class="media-field" data-media-field>
  <input type="hidden" name="{{ $name }}" value="{{ $value }}" data-media-id>
  <div class="d-flex align-items-center gap-3">
    <img src="{{ $m?->thumb_url }}" alt="" class="mf-preview {{ $m ? '' : 'd-none' }}" data-media-preview>
    <div class="d-flex flex-wrap gap-2">
      <button type="button" class="btn btn-sm btn-outline-secondary" data-media-pick><i class="fa-solid fa-images"></i> Choose from library</button>
      <button type="button" class="btn btn-sm btn-outline-danger {{ $m ? '' : 'd-none' }}" data-media-clear>Remove</button>
    </div>
  </div>
  <input type="file" name="{{ $upload }}" accept="image/jpeg,image/png,image/webp,image/gif" class="form-control form-control-sm mt-2">
  <div class="form-text">Or upload a new image (JPG, PNG, WebP or GIF, up to 5 MB). Large images are resized automatically.</div>
</div>
