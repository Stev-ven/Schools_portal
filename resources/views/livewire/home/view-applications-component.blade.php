        <div>
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-container  kt-container--fluid ">
                    <div class="kt-subheader__main">
                        <h3 class="kt-subheader__title">
                            All Applications
                        </h3>
                        <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                    </div>
                </div>
            </div>
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Registered Schools > {{ ucfirst($results[0]['school_name']) ?? 'Unknown School' }} >
                            Applications
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Application type</th>
                                <th>Aproval status</th>
                                <th>Payment status</th>
                                <th>Action</th>
                                <th>Date & time paid</th>
                                <th>Date & time added</th>
                                <th>More option</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @dd($results); --}}
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ str_replace('_', ' ', ucfirst($result['application_type'])) }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $result['approval_status'] == 'approved' ? 'badge-success' : 'badge-danger' }}">
                                            {{ ucfirst($result['approval_status']) }}
                                    </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $result['payment_status'] == 'paid' ? 'badge-success' : 'badge-danger' }}">
                                            {{ ucfirst($result['payment_status']) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($result['payment_status'] == 'pending')
                                            <button class="btn btn-outline-warning btn-label-warning" type="button"
                                                wire:click="editApplication('{{ addslashes(json_encode($result)) }}')">
                                                <i class="flaticon-edit"></i>Edit
                                            </button>
                                            @if ($result['application_type'] == 'application_for_authorization')
                                                <a href="{{ route('payForAfa', ['applicationId' => $result['application_id']]) }}"
                                                    class="btn rounded btn-label-info btn-sm btn-bold" type="button">
                                                    <i class="flaticon-payment"></i> Pay for application
                                                </a>
                                            @elseif($result['application_type'] == 'expression_of_interest')
                                                <a href="{{ route('payForEoi', ['applicationId' => $result['application_id']]) }}"
                                                    class="btn rounded btn-label-info btn-sm btn-bold" type="button">
                                                    <i class="flaticon-payment"></i> Pay for application
                                                </a>
                                            @elseif($result['application_type'] == 'notice_of_intent')
                                                <a href="{{ route('payForNoi', ['applicationId' => $result['application_id']]) }}"
                                                    class="btn rounded btn-label-info btn-sm btn-bold" type="button">
                                                    <i class="flaticon-payment"></i> Pay for application
                                                </a>
                                            @else
                                                <a href="{{ route('payForLoi', ['applicationId' => $result['application_id']]) }}"
                                                    class="btn rounded btn-label-info btn-sm btn-bold" type="button">
                                                    <i class="flaticon-payment"></i> Pay for application
                                                </a>
                                            @endif
                                        @else
                                            <button class="btn btn-warning" type="button"
                                                wire:click="editApplication('{{ addslashes(json_encode($result)) }}')">
                                                <i class="flaticon-edit"></i>Edit
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{$result['datetime_paid'] ?? '--'}}</td>
                                    <td>{{ $result['datetime_created'] }}</td>
                                    <td>
                                        <button class="btn btn-outline-info btn-label-info" type="button" role="alert">
                                            More info
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="kt-portlet__foot">
                {{-- <div class="kt-form__actions">
                    <button type="button" class="btn btn-outline-dark btn-label-dark"
                        wire:click="back">Go Back
                    </button>

                </div> --}}
            </div>
        </div>
