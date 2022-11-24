@extends('index')
@section('account_request')
<div class="for-inventory-rents">
    <div class="for-page-title">
        <h1>Request verification</h1>
    </div>
    <div class="table-reservation">
        <div class="error-message">
            @foreach ($errors->all() as $error)
            <div style="padding: 10px; margin:5px; background-color: #FF6464; color: #FFFFFF">{{$error}}</div>
            @endforeach
        </div>
        <div class="table-form">
            @if (!$accounts->isEmpty())
            <table>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Info</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                @foreach ($accounts as $account)
                <tr>

                    <td>{{ $account->id }}</td>
                    <td>
                        <img src="{{$account->validate->image}}" alt="" onclick="openImage('{{$account->validate->image}}')" />
                        <div class="show-id">
                            <svg  onclick="hide()" style="cursor: pointer;position: absolute; top: 0; right: 0; width: 50px; height: 50px; color: #F7F7F7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                              </svg>                
                            <img src="" id="verified_id">
                        </div>
                    </td>
                    <td>
                        <b>{{ $account->info->name}}</b>
                        <br>
                        <b>{{ $account->info->contact}}</b>
                        <br>
                        <b>{{ $account->info->email}}</b>
                    </td>
                    <td>{{ $account->validate->status == 0 ? "Unverified" : "Verified" }}</td>
                    <td>
                        <div class="action-form">
                            <div class="action-button">
                                <form action="{{ route('confirm_verification', $account->validate->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="action-print">
                                        Confirm
                                    </button>
                                </form>
                            </div>
                            <div class="action-button">
                                <form action="{{ route('reject_verification' , $account->validate->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="action-print">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
            @else
            <h1>No requests</h1>
            @endif
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.show-id').hide()
    });
    function openImage(url) {
        $('.show-id').show()
        $('#verified_id').attr('src', url)
    }
    function hide() {
        $('.show-id').hide()
    }
</script>
@endsection