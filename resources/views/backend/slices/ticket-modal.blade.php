<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-radius-0">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">New Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body mt-0 pt-0">
                <form id="modalTicketForm">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="mt-3 pb-0 row">
                        <input type="hidden" id="filter_tktdepartment">
                        <div class="col-md-6 mb-1">
                            <label for="tkt_type" class="form-label mb-0">Support Type *</label>
                            <select class="form-control mb-3 w-100 border-radius-0 select2_modal" id="tkt_type" data-width="100%">
                            </select>
                        </div>
                        <div class="col-md-6 mb-1">
                            <label for="location" class="form-label mb-0">Present Work Location *</label>
                            <select class="form-control mb-3 w-100 border-radius-0 select2_modal" id="location" data-width="100%">
                                <option selected="" disabled></option>
                                @foreach(App\Models\LookUp::orderBy('data', 'ASC')->where('type', 'location')->get() as $location)
                                <option value="{{$location->id}}">{{$location->data}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="message" class="form-label">Message:</label>
                        <textarea class="form-control border-radius-0" rows="5" maxlength="300" id="message" placeholder="This Message has a limit of 300 chars."></textarea>
                    </div>
                    <div class="mt-3">
                        <label class="form-label" for="customFile">File upload</label>
                        <input class="form-control border-radius-0" type="file" id="customFile">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSubminTicket">Create</button>
            </div>
        </div>
    </div>
</div>