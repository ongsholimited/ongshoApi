 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.news.master')
 @section('link')
     <link rel="stylesheet" href="{{ asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
     <link rel="stylesheet"
         href="{{ asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
     <link rel="stylesheet" href="{{ asset('vendor/ajax-file-uploader/css/jquery.uploader.css') }}">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css">
     <link rel="stylesheet" href="{{ asset('vendor/tagify/dist/tagify.css') }}">
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
     {{-- {{dd($post)}} --}}
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
                         <div class="col-12 col-md-9">
                             <div class="row">
                                 <div class="col-md-10 mr-auto ml-auto">
                                     <div class="form-group">
                                         <label for="recipient-name" class="col-form-label">Title:</label>
                                         <input type="text" class="form-control" id="title" placeholder="Write title"
                                             value="{{ $post->title }}">
                                         <div class="invalid-feedback" id="title_msg">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-md-10 mr-auto ml-auto">
                                     <div class="form-group">
                                         <label for="recipient-name" class="col-form-label">Link:</label>
                                         {{ URL::to('/') }}/<input type="text" class="form-control-sm" id="slug"
                                             placeholder="Write Slug" value="{{ $post->slug }}">
                                         <div class="invalid-feedback" id="slug_msg">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-md-10 mr-auto ml-auto">
                                     <div class="form-group">
                                         <label for="recipient-name" class="col-form-label">Short Description:</label>
                                         <textarea type="text" class="form-control" id="short_description" placeholder="Write short description">{{ $post->meta_description }}</textarea>
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
                                         <div id='content'></div>
                                         {{-- <textarea type="text" class="form-control" id="content" placeholder="write your content hare ..." >{{$post->content}}</textarea> --}}
                                         <div class="invalid-feedback" id="content_msg">
                                         </div>
                                     </div>
                                 </div>
                                 {{-- <textarea id="editor"></textarea> --}}
                                 <div class="col-md-10 mr-auto ml-auto">
                                     <div class="form-group">
                                         <label for="recipient-name" class="col-form-label">Focus Keyword:</label>
                                         <input class="d-block" id="focus_keyword" placeholder="Focus Keyword"
                                             value="{{ $post->focus_keyword }}">
                                         <div class="invalid-feedback" id="focus_keyword_msg">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-12 col-md-3">
                             <div class="form-group overflow-auto" style="max-height:200px;">
                                 <label for="">Category</label>
                                 @php
                                //  dd($post);
                                     $category = App\Models\News\Category::all();
                                     $i = 0;
                                 @endphp
                                 @foreach ($category as $cat)
                                     @php
                                         foreach ($post->categories as $pcat) {
                                             if ($cat->id == $pcat->id) {
                                                 $exist = true;
                                                 break;
                                             } else {
                                                 $exist = false;
                                             }
                                         }
                                     @endphp
                                     <div class="form-check">
                                         <input class="form-check-input" type="checkbox" name='category[]'
                                             {{ $exist ? 'checked' : '' }}>
                                         <label class="form-check-label" for="category">
                                             {{ $cat->name }}
                                         </label>
                                     </div>
                                     @php
                                         $i++;
                                     @endphp
                                 @endforeach
                             </div>
                             <div class="input-group">
                                <select type="text" class="form-control" placeholder="User" id="user">
                                
                                </select>
                                <div class="input-group-append">
                                  <button class="btn btn-outline-secondary" id="add_author" type="button">Add</button>
                                </div>
                                  
                             </div>
                             @foreach($post->author as $user)
                                <div class="d-block" id='users'>
                                    <div class='m-1'>
                                        <input type="hidden" value='{{$user->details->id}}' name='author[]'>
                                        <span class>{{$user->details->first_name.' '.$user->details->last_name}}</span>
                                        <button class="btn btn-xs btn-danger ml-1 float-right removeAuthor">X</button>
                                    </div>
                                </div>
                             @endforeach
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
                         <button class="btn btn-sm btn-primary "><i class="fas fa-folder"></i> <i
                                 class="fas fa-plus"></i></button>
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
     @include('news.post_preview.internal-assets.js.script')
     <script>
      let editorInstance;
          ClassicEditor
             .create(document.querySelector('#content'))
             .then(editor => {
                 editorInstance=editor;
                 xyz = <?php echo $post->content; ?>
                 // xyz=`<pre><code class="language-typescript">import Editor from 'ckeditor5-custom-build/build/ckeditor';     // import { CKEditor } from "@ckeditor/ckeditor5-react";     // import Card from "Resources/components/Card";     // import PostCardProfile from "Resources/components/PostCardProfile";     // import PostLove from "Resources/components/PostLove";     // import parse from "html-react-parser";</code></pre>     // <p><img class="image_resized" style="width:62.76%;" src="https://images.unsplash.com/photo-1501493870936-9c2e41625521?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1045&amp;q=80" alt="nc blog"></p>          <p><img src="https://images.unsplash.com/photo-1501493870936-9c2e41625521?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1045&amp;q=80" alt="nc blog"></p>          <figure class="image image_resized" style="width:39.05%;"><img src="https://images.unsplash.com/photo-1501493870936-9c2e41625521?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1045&amp;q=80" alt="nc blog"></figure>          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,&nbsp;</p><figure class="image image-style-side"><img src="https://images.unsplash.com/photo-1501493870936-9c2e41625521?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1045&amp;q=80" alt="nc blog"></figure><p>sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem</p>          <figure class="image image-style-side"><img src="https://images.unsplash.com/photo-1501493870936-9c2e41625521?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1045&amp;q=80" alt="nc blog"></figure><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem.</p>          <figure class="image"><img src="https://images.unsplash.com/photo-1501493870936-9c2e41625521?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1045&amp;q=80" alt="nc blog"></figure>          <figure class="table"><table><tbody><tr><td><figure class="media"><div data-oembed-url="https://vimeo.com/524933864"><div style="position: relative; padding-bottom: 100%; height: 0; padding-bottom: 56.2493%;"><iframe src="https://player.vimeo.com/video/524933864" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0;" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe></div></div></figure></td><td><figure class="media"><div data-oembed-url="https://youtu.be/H08tGjXNHO4"><div style="position: relative; padding-bottom: 100%; height: 0; padding-bottom: 56.2493%;"><iframe src="https://www.youtube.com/embed/H08tGjXNHO4" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe></div></div></figure></td></tr></tbody></table></figure>          <figure class="table"><table style="border:1px solid hsl(0, 0%, 0%);"><tbody><tr><td style="border:1px solid hsl(0, 0%, 0%);">a</td><td style="border:1px solid hsl(0, 0%, 0%);">c</td></tr><tr><td style="border:1px solid hsl(0, 0%, 0%);">b</td><td style="border:1px solid hsl(0, 0%, 0%);">d</td></tr></tbody></table></figure>          <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Iure vel officiis ipsum placeat itaque neque dolorem modi perspiciatis dolor distinctio veritatis sapiente, minima corrupti dolores necessitatibus suscipit accusantium dignissimos culpa cumque.</p><p>It is a long established fact that a <strong>reader</strong> will be distracted by the readable content of a page when looking at its <strong>layout</strong>. The point of using Lorem Ipsum is that it has a more-or-less normal <a href="https://ncmaz-react.vercel.app/#">distribution of letters.</a></p>      <ol><li>We want everything to look good out of the box.</li><li>Really just the first reason, that's the whole point of the plugin.</li><li>Here's a third pretend reason though a list with three items looks more realistic than a list with two items.</li></ol>   <h3><strong>Typography should be easy</strong></h3>      <p>So that's a header for you — with any luck if we've done our job correctly that will look pretty reasonable.</p><p>Something a wise person once told me about typography is:</p><blockquote><p><i>Typography is pretty important if you don't want your stuff to look like trash. Make it good then it won't be bad.</i></p></blockquote><p>It's probably important that images look okay here by default as well:</p>      <blockquote><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tem ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud ullamco laboris nisi ut aliquip ex ea commodo onsequat.</p><h4>- Rosalina Pong</h4></blockquote><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem.</p><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit <a href="https://ncmaz-react.vercel.app/single-3/demo-slug">voluptatem accusantium doloremque laudantium</a>, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem.</p>            // `
                 editor.setData(xyz, {
                     mode: 'change'
                 });
                 // data=editor.getData();
                 //       console.log(editor.getData() );
                 //       editor.setData(data);
             })
             .catch(error => {
                 console.error(error);
             });
     </script>
 @endsection
