<div class="modal-header">
    <h5 class="modal-title h6">{{ translate('Refund Request Information') }}</h5>
    <button type="button" class="close" data-dismiss="modal">
    </button>
</div>
<div class="modal-body">
    <div class="table-responsive">
        <table class="table mar-no">
            <tbody>
                <tr>
                    <td>{{ translate('Refund Reason') }}</td>
                    <td>
                        @php $refund_reasons = json_decode($refund_request->reasons ?? '[]'); @endphp
                        @foreach ($refund_reasons as $refund_reason)
                            <p>{{ $refund_reason }}</p>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td>{{ translate('Note') }}</td>
                    <td>{{ $refund_request->refund_note }}</td>
                </tr>
                <tr>
                    <td>{{ translate('Attachements') }}</td>
                    <td>
                        @foreach (explode(',', $refund_request->attachments) as $key => $attachment_id)
                            @php $attachment = \App\Models\Upload::find($attachment_id);  @endphp
                            @if ($attachment)
                                @if ($attachment->type == 'image')
                                    <div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" title="{{ $attachment->file_name }}">
                                        <a href="{{ route('download_attachment', $attachment->id) }}" target="_blank" class="d-block text-reset">
                                            <div class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                <img src="{{ my_asset($attachment->file_name) }}" class="img-fit">
                                            </div>
                                            <div class="col body">
                                                <h6 class="d-flex">
                                                    <span class="text-truncate title">{{ $attachment->file_original_name }}</span>
                                                    <span class="ext flex-shrink-0">.{{ $attachment->extension }}</span>
                                                </h6>
                                                <p>{{ formatBytes($attachment->file_size) }}</p>
                                            </div>
                                        </a>
                                    </div>                                    
                                @else
                                    <div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" title="{{ $attachment->file_name }}">
                                        <a href="{{ route('download_attachment', $attachment->id) }}" target="_blank" class="d-block text-reset">
                                            <div class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                <i class="la la-file-text"></i>
                                            </div>
                                            <div class="col body">
                                                <h6 class="d-flex">
                                                    <span class="text-truncate title">{{ $attachment->file_original_name }}</span>
                                                    <span class="ext flex-shrink-0">.{{ $attachment->extension }}</span>
                                                </h6>
                                                <p>{{ formatBytes($attachment->file_size) }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-secondary" role="alert">
                                    {{ translate('No attachment') }}
                                </div>
                            @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
