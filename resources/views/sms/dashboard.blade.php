@extends('layouts.sms.master')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-eye"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">todays send</span>
                            <span class="info-box-number">
                                1000
                                <small></small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-pen"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">total Send</span>
                            <span class="info-box-number">
                                50
                                <small></small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Month Send</span>
                            <span class="info-box-number">
                                50
                                <small></small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Todays Cost</span>
                            <span class="info-box-number">
                                0
                                <small></small>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
            @endsection
