<div>
    <div>
        <!-- Main content -->
        <div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 order-lg-1 order-xl-1">
                    <!--begin:: Widgets/Application Sales-->
                    <div class="kt-portlet kt-portlet--height-fluid" style="margin-top: 15px;">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Schools Not Pending License Renewal
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="kt_widget11_tab1_content">

                                    <!--begin::Widget 11-->
                                    <div class="kt-widget11">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                @if($paginatedData->isEmpty())
                                                    <div>
                                                        <h5>No data found</h5>
                                                    </div>
                                                @else
                                                <thead>
                                                    <tr>
                                                        <td style="width: 1%;">#</td>
                                                        <td style="width: 20%;">School Name</td>
                                                        <td style="width: 15%;">Type of School</td>
                                                        <td style="width: 10%;">Application</td>
                                                        <td style="width: 15%;">Date submitted</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($paginatedData as $index => $schoolRegistered)
                                                        <tr>
                                                            <td>
                                                                <span class="text-muted">
                                                                    {{ $loop->iteration + $paginatedData->firstItem() - 1 }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $schoolRegistered['school_name'] }}</td>
                                                            <td>{{ $schoolRegistered['type_of_school'] }}</td>
                                                            <td>
                                                                <button class="btn rounded btn-label-info btn-sm btn-bold"
                                                                 data-toggle="modal"
                                                                 data-target="#viewApplicationModal"
                                                                 data-school-name="{{ $schoolRegistered['school_name'] }}"
                                                                 data-application-id="{{ $schoolRegistered['id'] }}"
                                                                 data-special-school-id="{{ $schoolRegistered['special_school_id'] }}">
                                                                    <i class="flaticon-eye"></i> View Application
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <span class="kt-badge kt-badge--warning kt-badge--inline">
                                                                    {{ $schoolRegistered['date_submitted'] }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="kt-widget11__action kt-align-right">
                                            <button wire:click="viewSchPendingRenewal" type="button" class="btn btn-label-success btn-sm btn-bold">
                                                <span wire:loading.remove wire:target="viewSchPendingRenewal">Schools Pending License Renewal</span>
                                                <span wire:loading wire:target="viewSchPendingRenewal" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                                <span wire:loading wire:target="viewSchPendingRenewal" role="status">Loading...</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                @foreach ($paginationData as $page)
                    <li class="page-item {{ $page['active'] == 'yes' ? 'active' : '' }}">
                        <button class="page-link" wire:click="gotoPage({{ $page['page'] }})">{{ $page['text'] }}</button>
                    </li>
                @endforeach
            </ul>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="viewApplicationModal" tabindex="-1" role="dialog" aria-labelledby="viewApplicationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewApplicationModalLabel">Application Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> <span id="modal-school-name"></span></p>
                        <p><strong>Application ID:</strong> <span id="modal-application-id"></span></p>
                        <p><strong>Special School ID:</strong> <span id="modal-special-school-id"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function(){
        $('#viewApplicationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var schoolName = button.data('school-name');
            var applicationId = button.data('application-id');
            var specialSchoolId = button.data('special-school-id');


            var modal = $(this);
            modal.find('#modal-school-name').text(schoolName);
            modal.find('#modal-application-id').text(applicationId);
            modal.find('#modal-special-school-id').text(specialSchoolId);
        });
    });
    </script>

</div>
