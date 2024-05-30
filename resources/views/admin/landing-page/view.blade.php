@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Enquiry Details</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminLandingPageEnquiry') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" class="form" action="">
                    <div class="card-body border-top p-9">

                       <table class="table table-bordered">
                            <tr>
                               <th>Product</th>
                               <td>{{ $data->product }}</td>
                            </tr>
                            <tr>
                               <th>Sub Product</th>
                               <td>{{ $data->options }}</td>
                            </tr>
                            <tr>
                               <th>Name</th>
                               <td>{{ $data->name }}</td>
                            </tr>
                            <tr>
                               <th>Phone Number</th>
                               <td>{{ $data->phone_number }}</td>
                            </tr>
                            <tr>
                               <th>Email</th>
                               <td>{{ $data->email }}</td>
                            </tr>
                            <tr>
                               <th>Location</th>
                               <td>{{ $data->location }}</td>
                            </tr>
                            <tr>
                               <th>No of Pages</th>
                               <td>{{ $data->no_of_pages }}</td>
                            </tr>
                            <tr>
                               <th>No of Copies</th>
                               <td>{{ $data->no_of_copies }}</td>
                            </tr>   
                            <tr>
                               <th>Requirements</th>
                               <td>{{ $data->requirement_specification }}</td>
                            </tr>   
                            <tr>
                               <th>Date</th>
                               <td>{{ date('d-m-Y h:i A', strtotime($data->created_at)) }}</td>
                            </tr>   
                       </table>

                    </div>      
                </form>
            </div>
        </div>

    </div>
</div>
@endsection