

<form action="{{route('Qr-table.post')}}" method="POST" class="pt-4">
    @csrf
    <div class="mb-3 row">
        <label for="inputPassword" class="col-sm-2 col-form-label"> Meja </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="input" name="meja" placeholder="Exampel: Lt1-001 or VIP-1-Lt3" required>
        </div>
    </div>
    <button class="btn btn-submit btn-primary text-end w-100" type="submit">Create</button>
</form>