<?php
session_start();
if ($_SESSION['admin'] == 'yes') {
?>
<div id="buy_cards" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buy Extra Cards</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form onsubmit ="return false;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>No. of Cards</label>
                                    <input type="number" value="0" min="1" id="total-card" name="total-card" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-center mb-0">
                                <thed>
                                    <th>No</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quntity</th>
                                    <th>GST (18%)</th>
                                    <th>Total</th>
                                </thed>
                                <tbody>
                                    <td>1</td>
                                    <td>Employee</td>
                                    <td>₹40</td>
                                    <td><span id="cards-quintity">0</span></td>
                                    <td>₹<span id="cards-gst">0</span></td>
                                    <td>₹<span id="cards-total">0</span></td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="submit" id="buyextra-employee">Buy Cards</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php }