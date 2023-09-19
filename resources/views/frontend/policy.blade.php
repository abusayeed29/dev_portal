@extends('layouts.master')

@section('title','Group policies')

@push('css')

@endpush

@section('content')

    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Privacy</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h6 class="card-title">Privacy</h6> -->
                       
                        <div class="terms_condition-content">
                            <!-- <div class="terms_condition-text mb-5">
                                <h4 class="card-title card-title-1">Facilities Administration & Security Policies: </h4>
                            </div>


                            <div class="terms_condition-text mb-5">
                                <h4 class="card-title card-title-1">Finance Policies: </h4>
                            </div> -->

                            <div class="terms_condition-text mb-5">
                                <!-- <h4 class="card-title card-title-1">Human Resources Policies: </h4> -->
                                <ul>
                                    <li>
                                        <a href="{{asset('uploads/policy/Policy-Anti-Harassment-Threat-of-Violence.pdf')}}" target="_blank">
                                            <i class="bi bi-circle-fill"></i>
                                            <p>Policy - Anti Harassment & Threat of Violence.pdf</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{asset('uploads/policy/Policy-Conflict-of-Interest.pdf')}}" target="_blank">
                                            <i class="bi bi-circle-fill"></i>
                                            <p>Policy - Conflict of Interest.pdf</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{asset('uploads/policy/Policy-Prohibition-of-Sexual-Harassment.pdf')}}" target="_blank">
                                            <i class="bi bi-circle-fill"></i>
                                            <p>Policy - Prohibition of Sexual Harassment.pdf</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{asset('uploads/policy/Policy-Whistleblower.pdf')}}" target="_blank">
                                            <i class="bi bi-circle-fill"></i>
                                            <p>Policy - Whistleblower.pdf</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- <div class="terms_condition-text mb-5">
                                <h4 class="card-title card-title-1">Information Technology Policies: </h4>
                            </div> -->
                        </div>
                           
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('js')

@endpush    