 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.news.master')
 @section('link')
 <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendor/ajax-file-uploader/css/jquery.uploader.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css">
  <link rel="stylesheet" href="{{asset('vendor/tagify/dist/tagify.css')}}">
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
      <div class="container-fluid">

        <div class="card ">
            <div class="card-header bg-dark">
              <div class="row">
                <div class="col-6">
                  <div class="card-title">Post </div>
                </div>
                <div class="col-6">
                  <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal" data-whatever="@mdo">Add New</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form>
                <input type="hidden" id="id">
                <div class="row">
                  <div class="col-md-10 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Parent Category:</label>
                      <select name="" id="category" class="form-control"></select>
                      <div class="invalid-feedback" id="category_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Title:</label>
                      <input type="text" class="form-control" id="title" placeholder="Write title">
                      <div class="invalid-feedback" id="title_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Short Description:</label>
                      <textarea type="text" class="form-control" id="short_description" placeholder="Write short description"></textarea>
                      <div class="invalid-feedback" id="short_description_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10 mr-auto ml-auto">
                    <button type='button' class="btn btn-danger" data-toggle="modal" data-target="#modal">
                      UPLOAD IMAGES
                    </button>
                    
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Content:</label>
                      <textarea type="text" class="form-control" id="content" placeholder="write your content hare ..." ></textarea>
                      <div class="invalid-feedback" id="content_msg">
                      </div>
                    </div>
                  </div>
                  {{-- <textarea id="editor"></textarea> --}}
                  <div class="col-md-10 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Tags:</label>
                      <input class="d-block"  id="tags" placeholder="Write Tags">
                      <div class="invalid-feedback" id="tags_msg">
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="card-footer m-auto">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Clear</button>
              <button type="button" class="btn btn-primary" onclick="submitPost()">Submit</button>
            </div>
          </div>
      </div><!-- /.container-fluid -->
      {{-- modal --}}
      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                  <button class="btn btn-sm btn-primary "><i class="fas fa-folder"></i> <i class="fas fa-plus"></i></button>
                  <input type="text" class="form-control-sm" id='folder_name'>
                  <div class="invalid-feedback" id="name_msg"></div>
                  <button class="btn btn-sm btn-primary" onclick="createFolder()">Add</button>
                  <span id="all-folders"></span>
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
              <button type="button" class="btn btn-primary" onclick="formRequest()">Insert</button>
            </div>
          </div>
        </div>
      </div>
      {{-- endmodal --}}
    </section>
  @endsection
  @section('script')
    <script src="{{asset('storage/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('storage/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    <script src="{{asset('vendor/ajax-file-uploader/dist/jquery.uploader.min.js')}}"></script>
    <script src="{{asset('vendor/tagify/dist/tagify.js')}}"></script>
  @include('news.post.internal-assets.js.script')
  @endsection