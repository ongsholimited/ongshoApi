 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.news.master')
 @section('link')
     <link rel="stylesheet" href="{{ asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
     <link rel="stylesheet"
         href="{{ asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
     <link rel="stylesheet" href="{{ asset('vendor/ajax-file-uploader/css/jquery.uploader.css') }}">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css">
     <link rel="stylesheet" href="{{ asset('vendor/tagify/dist/tagify.css') }}">
     <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
     <style>
      .ck-powered-by{
        display:none;
      }
     </style>
 @endsection
 @section('content')
     <!-- Content Header (Page header) -->
     <div class="content-header">
         <div class="container-fluid">
             <div class="row mb-2">
                 <div class="col-sm-6">
                     <h1 class="m-0">Post</h1>
                 </div><!-- /.col -->
                 <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                         <li class="breadcrumb-item"><a href="#">Home</a></li>
                         <li class="breadcrumb-item active">Post</li>
                     </ol>
                 </div><!-- /.col -->
             </div><!-- /.row -->
         </div><!-- /.container-fluid -->
     </div>
     <!-- /.content-header -->
     <!-- Main content -->
     <section class="content">
         <div class="card ">
             <div class="card-header bg-dark">
                 <div class="row">
                     <div class="col-6">
                         <div class="card-title">Post </div>
                     </div>
                     <div class="col-6">
                         <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal"
                             data-whatever="@mdo">Add New</button>
                     </div>
                 </div>
             </div>
             <div class="card-body">
                 <form>
                     <input type="hidden" id="id">
                     <div class="row">
                         
                         <div class="col-12 col-md-12">
                             <div class="row">
                              <div class="col-md-10 mr-auto ml-auto">
                                <div class="form-group">
                                  <label for="">Title</label>
                                  <input class="form-control" name="title" id="title" placeholder="title" value="{{$post->title}}">
                                  <div class="invalid-feedback" id="msg_title"></div>
                                </div>
                                <div class="form-group">
                                  <label for="">Slug</label>
                                  <input class="form-control" name="slug" id="slug" placeholder="slug" value="{{$post->slug->slug_name}}">
                                  <div class="invalid-feedback" id="msg_slug"></div>
                                </div>
                              </div>
                              <div class="col-md-10 mr-auto ml-auto">
                                <div class="form-group">
                                  <label for="">Description</label>
                                  <textarea class="form-control" name="description" id="description" rows="2" placeholder="description" >{{$post->description}}</textarea>
                                  <div class="invalid-feedback" id="msg_description"></div>
                                </div>
                              </div>
                                 <div class="col-md-10 mr-auto ml-auto">
                                     <button type='button' class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="showImage()">
                                         Add Image
                                     </button>

                                     <div class="form-group">
                                         <label for="recipient-name" class="col-form-label">Content:</label>
                                         <div id='content'></div>
                                         <div class="invalid-feedback" id="content_msg">
                                         </div>
                                     </div>
                                 </div>
                                 {{-- <textarea id="editor"></textarea> --}}
                                 <div class="col-md-10 mr-auto ml-auto">
                                     <div class="form-group">
                                         <label for="recipient-name" class="col-form-label">Focus Keyword:</label>
                                         <input class="d-block" id="focus_keyword" placeholder="Focus Keyword"
                                             value="{{$post->keyword}}">
                                         <div class="invalid-feedback" id="focus_keyword_msg">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-md-10 mr-auto ml-auto">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Status:</label>
                                        <select class="form-control" name="status" id="status" value="{{$post->status}}">
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select>
                                        <div class="invalid-feedback" id="focus_keyword_msg">
                                        </div>
                                    </div>
                                </div>
                             </div>
                             <div class="mt-5">
                                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Clear</button> --}}
                                <button type="button" class="btn btn-primary" onclick="submitPost()">Submit</button>
                            </div>
                         </div>
                         
                         <div class="col-12 col-md-3">
                             {{-- end schedule --}}
                             {{-- submit btn --}}
                             
                             {{-- submit btn --}}
                         </div>
                     </div>
             </div>
             </form>
         </div>
         {{-- <div class="card-footer m-auto">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Clear</button>
             <button type="button" class="btn btn-primary" onclick="submitPost()">Submit</button>
         </div> --}}
         </div>
         {{-- modal --}}
         <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true" id="modal">
             <div class="modal-dialog modal-lg">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                     </div>
                     <div class="modal-body">
                         
                         <div class="row all-images mt-2 p-2">

                         </div>
                         <div>
                             <input type="text" id="file">
                         </div>

                         <div class="">
                             <button class="btn btn-sm btn-primary" onclick="formRequest()">Upload</button>
                         </div>

                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         <button type="button" disabled id='insert' class="btn btn-primary" onclick="insert()">Insert</button>
                     </div>
                 </div>
             </div>
         </div>
         {{-- endmodal --}}
     </section>
 @endsection
 @section('script')
     <script src="{{ asset('storage/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
     <script src="{{ asset('storage/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
     <script src="{{ asset('storage/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
     <script src="{{ asset('storage/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js"
         integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ=="
         crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
     <script src="{{ asset('vendor/ajax-file-uploader/dist/jquery.uploader.min.js') }}"></script>
     <script src="{{ asset('vendor/tagify/dist/tagify.js') }}"></script>
     <script src="{{ asset('storage/plugins/ckeditor/build/ckeditor.js') }}"></script>
     <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
     <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
     <script>
         var editorInstance;
         ClassicEditor
             .create(document.querySelector('#content'))
             .then(editor => {
                 editorInstance = editor;
                 xyz = wrapWithBackticks(`<?php echo $post->content; ?>`);
                 editor.setData(xyz, {
                     mode: 'change'
                 });
             })
             .catch(error => {
                 console.error(error);
             });

         function wrapWithBackticks(string) {
             return `${string}`;
         }
     </script>
     @include('news.pages.internal-assets.js.script-edit')
 @endsection
