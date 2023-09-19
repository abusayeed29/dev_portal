                    <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                        </li>
                        @foreach(App\Models\TktDepartment::orderBy('id','ASC')->get() as $departments)
                        <li class="nav-item">
                            <a class="nav-link" id="{{$departments->slug}}-line-tab" data-bs-toggle="tab" href="#{{$departments->slug}}" role="tab" data-url="{{$departments->slug}}" aria-controls="{{$departments->name}}" aria-selected="true">{{$departments->name}}</a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content mt-3" id="tktDeprtTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-line-tab">
                            <div class="table-responsive">
                                <table id="dataTableTicket" class="table">
                                    <thead>
                                        <tr>
                                            <th>#Token</th>
                                            <th>Message</th>
                                            <th>Category</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th>STATUS</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ticketrow_id">
                                        
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>