<form action="{{ route('client.store') }}" method="POST" class="ajaxPostFormSave">
    @csrf
    <div class="card-body">
        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" class="form-control" id="name" placeholder="Ex: Somnath Paik" name="name">
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" class="form-control" id="email" placeholder="Ex: somnathpaik@gmail.com" name="email">
        </div>
        <div class="form-group">
            <label for="mobile">Mobile *</label>
            <input type="tel" class="form-control" id="mobile" placeholder="+919876543210" name="mobile">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1">
            <label class="form-check-label" for="is_active">Approved ?</label>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>