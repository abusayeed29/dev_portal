<div class="modal fade" id="vmsRequisitionModal" tabindex="-1" role="dialog" aria-labelledby="vmsRequisitionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-radius-0">
            <div class="modal-header">
                <h5 class="modal-title" id="vmsRequisitionLabel">New Requistion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body mt-0 pt-0">
                <form id="addRequisitionForm">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="mt-3 pb-0 row">
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="is_dhaka" id="isDhaka" value="0" checked>
                                <label class="form-check-label" for="isDhaka">
                                    Is Inside Dhaka Division or Projects (Anthuriam, Highland, Southern Ville...) Visit ? 
                                </label>
                            </div>
                            <div class="form-check form-check-inline outDhaka">
                                <input type="radio" class="form-check-input" name="is_dhaka" id="isDhaka1" value="1">
                                <label class="form-check-label" for="isDhaka1">
                                    Is Outside Dhaka Division?
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="location" class="form-label mb-0">Where From  *</label>
                            <input type="text" name="pick_from" class="form-control" id="" autocomplete="off" placeholder="">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="location" class="form-label mb-0">Where To  *</label>
                            <input type="text" name="drop_to" class="form-control" id="" autocomplete="off" placeholder="">
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="location" class="form-label mb-0">Time From *</label>
                            <div class="input-group flatpickr" id="flatpickr-time-from">
                                <input type="text" name="pick_time" class="form-control" placeholder="Select time" data-input>
                                <span class="input-group-text input-group-addon" data-toggle><i data-feather="clock"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="location" class="form-label mb-0">Time To *</label>
                            <div class="input-group flatpickr" id="flatpickr-time-to">
                                <input type="text" name="drop_time" class="form-control" placeholder="Select time" data-input>
                                <span class="input-group-text input-group-addon" data-toggle><i data-feather="clock"></i></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-1 mt-1">
                            <label for="location" class="form-label mb-0">Nubmer of Traveller*</label>
                            <input type="number" name="no_person" class="form-control" id="" autocomplete="off" placeholder="">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="message" class="form-label">Purpose: *</label>
                        <textarea name="description" class="form-control border-radius-0" rows="3" maxlength="300" id="message" placeholder="This Message has a limit of 300 chars."></textarea>
                    </div>
                    <input type="hidden" name="is_team" id="isTeam" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSubmitRequisition">Create</button>
            </div>
        </div>
    </div>
</div>