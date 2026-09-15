<x-layouts.master>
    <!--== Start Page Content Wrapper ==-->
    <div class="page-content-wrapper sm-top sm-bottom">
        <div class="about-page-content">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-12 order-1 order-md-0">
                        <div class="about-content text-center">
                         <img src="assets/installment/bog.jpeg" class="insta-image img-fluid" alt="Logo"/>
                         <p class="text-warning fs-1">Coming soon</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 order-1 order-md-0">
                        <div class="about-content text-center">
                         <img src="assets/installment/tbc.jpeg" class="insta-image img-fluid" alt="Logo"/>
                         <p class="text-warning fs-1">Coming soon</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 order-1 order-md-0">
                        <div class="about-content text-center">
                            <div data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <img src="assets/installment/credo.jpeg" class="insta-image img-fluid" alt="Logo"/>
                                <p class="text-success fs-1">შეავსეთ განაცხადი</p>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">

                                  <div class="modal-body">
                                    <img src="assets/installment/qqr.jpeg" class="insta-image img-fluid" alt="Logo"/>
                                    <a href="https://tiny.keepz.me/2zc56t5c" target="_blank">https://tiny.keepz.me/2zc56t5c</a>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== End Page Content Wrapper ==-->
@push('styles')
<style>
    .insta-image{
        max-height: 240px;
        margin: auto;
    }
</style>
@endpush
</x-layouts.master>

