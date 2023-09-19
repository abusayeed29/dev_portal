<div class="modal fade" id="approveRequisitionModal" tabindex="-1" role="dialog" aria-labelledby="approveRequisitionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-radius-0">
            <div class="modal-header">
                <h5 class="modal-title" id="approveRequisitionModalLabel">Are you want to Approve?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <p id="message_body"></p>
                <form id="requisitionApproveForm">
                    <div class="mb-4 mt-3">
                        <div class="form-check mb-2">
                            <input type="radio" class="form-check-input" value="1" name="is_approve" id="approve">
                            <label class="form-check-label" for="approve">
                                Approve
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="radio" class="form-check-input" value="0" name="is_approve" id="not_approve">
                            <label class="form-check-label" for="not_approve">
                                Not Approve
                            </label>
                        </div>
                    </div>
                    <div class="mb-4 mt-3" id="notapprove_box" style="display: none">
                        <label class="form-label mb-0" for="reason">Rejected Reason *</label>
                        <input type="text" class="form-control" name="reason" id="reason">
                    </div>

                    <input type="hidden" name="requisition_id" id="requisition_id" value="">
                    <input type="hidden" name="stage" id="stageId" value="">
                    <input type="hidden" name="is_team" id="isTeam" value="">

                    <div class="mb-4 mt-3 row" id="driverAssign">
                        
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnApproveSubmit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>