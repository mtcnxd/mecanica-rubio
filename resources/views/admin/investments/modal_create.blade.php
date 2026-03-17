<div class="modal fade" id="addShopping" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('bitso.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="mb-2">Book</label>
                            <select id="parity" class="form-select" name="book">
                                <option>btc_mxn</option>
                                <option>eth_mxn</option>
                                <option>xrp_mxn</option>
                                <option>btc_usdt</option>
                                <option>eth_usdt</option>
                                <option>xrp_usdt</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="mb-2">Cantidad</label>
                            <input type="text" name="amount" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="mb-2">Precio de compra</label>
                        <input type="text" name="price" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="insertData">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>