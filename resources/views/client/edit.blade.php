<form action="{{ route('client.update', [$client->uuid]) }}" method="POST" class="ajaxPostFormSave">
    @csrf
    <div class="card-body">
        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" class="form-control" id="name" placeholder="Ex: Somnath Paik" name="name" value="{{ old('name', $client->name) }}">
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" class="form-control" id="email" placeholder="Ex: somnathpaik@gmail.com" name="email" value="{{ old('email', $client->clientContact->email) }}" readonly>
        </div>
        <div class="form-group">
            <label for="mobile">Mobile *</label>
            <input type="tel" class="form-control" id="mobile" placeholder="+919876543210" name="mobile" value="{{ old('mobile', $client->clientContact->mobile) }}" readonly>
        </div>
        <div class="form-group">
        <label for="status">Status</label><br>
        <div class="selector">
            <div class="selecotr-item">
                <input type="radio" id="radio1" name="is_active" value="1" class="selector-item_radio active_radio" @if(old('is_active', $client->is_active) == 1) checked @endif>
                <label for="radio1" class="selector-item_label">Active</label>
            </div>
            <div class="selecotr-item">
                <input type="radio" id="radio2" name="is_active" value="2" class="selector-item_radio inactive_radio" @if(old('is_active', $client->is_active) == 2) checked @endif>
                <label for="radio2" class="selector-item_label">Inactivate</label>
            </div>
        </div>        
        </div>

    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
<script>
    $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })
</script>