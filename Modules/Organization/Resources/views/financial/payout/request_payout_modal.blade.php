<div class="modal fade admin-query" id="request_payout_modal">
    <div class="modal-dialog modal_1000px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('organization.payout_confirmation')}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>

            <div class="modal-body">
                <p class="my-2">Note: Please check the following information for your payout request, we will transfer
                    money to following financial <a href="{{route('users.settings')}}">accounts</a>.
                    If a financial account is not set up, you will not be able to request a payout.</p>
                <form action="{{route('organization.payout.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-15">
                        <div class="col-xl-6  d-flex justify-content-between">
                            <span class="h3">{{__('organization.ready_to_payout')}}</span>
                            <span class="h3">{{showPrice($ready_to_payout)}}</span>
                        </div>

                    </div>

                    <div class="col-lg-12 text-center pt_15 mt-20">
                        <div class="d-flex justify-content-center">
                            @if(auth()->user()->userPayoutAccount)
                                <button class="primary-btn semi_large2 fix-gr-bg"
                                        type="submit"> {{__('organization.request_payout')}}</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
