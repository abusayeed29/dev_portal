<div class="modal fade" id="releaseModal" tabindex="-1" role="dialog" aria-labelledby="releaseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-radius-0">
            <div class="modal-header">
                <h5 class="modal-title" id="releaseModalLabel">Are you sure you want to release?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <p id="message_body"></p>
                <form id="requisitionReleaseForm">
                    <div class="mb-4 mt-3">
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="release_time" id="release_time">
                            <label class="form-check-label" for="release_time">
                                Yes
                            </label>
                        </div>
                    </div>
                    <input type="hidden" name="req_id" id="req_id" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnReleaseSubmit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>