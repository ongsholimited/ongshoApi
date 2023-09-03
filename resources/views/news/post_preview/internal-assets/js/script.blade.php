<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{URL::to('news/post-list')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'title',
            name:'title',
          },
          {
            data:'meta_description',
            name:'meta_description',
          },
          {
            data:'author',
            name:'author',
          },
          {
            data:'status',
            name:'status',
          },
          {
            data:'action',
            name:'action',
          }
        ],
        "columnDefs": [
            { className: "text-left", "targets": [ 1,2,3 ] }
        ]
    });
  })
    
  // $('#content').trumbowyg();
window.formRequest= function(){
    $('input,select').removeClass('is-invalid');
    let galary=$("input[name='folder[]']:checked").val();
    let id=$('#id').val();
    if(galary==undefined){
      galary='';
    }
    var formData= new FormData();
    for(i=0;i<imagesFiles.length;i++){
      formData.append('images[]',imagesFiles[i]);
    }
    formData.append('galary',galary);
    $('#exampleModalLabel').text('Add New Category');
    
    //axios post request
    axios.post("{{route('news.images.store')}}",formData)
    .then(function (response){
        if(response.data.message){
            toastr.success(response.data.message)
            showImage()
        }else if(response.data.error){
          var keys=Object.keys(response.data.error);
          keys.forEach(function(d){
            $('#'+d).addClass('is-invalid');
            $('#'+d+'_msg').text(response.data.error[d][0]);
          })
        }
    })
}

function submitPost(){
    $('input,select').removeClass('is-invalid');
    let category=$("#category").val();
    let title=$("#title").val();
    let short_description=$("#short_description").val();
    let content=$("#content").val();
    let tag=$("#tags").val();
    console.log(tags)
    if(tag!=''){
      tags=JSON.parse(tag);
      tag="";
      tags.forEach(function(d){
        tag+=d.value+',';
      })
      console.log(tag);
    }
    

    var formData= new FormData();
    
    formData.append('category',category);
    formData.append('title',title);
    formData.append('short_description',short_description);
    formData.append('content',content);
    formData.append('tags',tag);
    $('#exampleModalLabel').text('Add New Post');
    
    //axios post request
    axios.post("{{route('news.post.store')}}",formData)
    .then(function (response){
        if(response.data.message){
            toastr.success(response.data.message)
            showImage()
        }else if(response.data.error){
          var keys=Object.keys(response.data.error);
          keys.forEach(function(d){
            $('#'+d).addClass('is-invalid');
            $('#'+d+'_msg').text(response.data.error[d][0]);
          })
        }
    })

}
$(document).delegate("#modalBtn", "click", function(event){
    clear();
    $('#exampleModalLabel').text('Add New Post');
});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Update Post');
    let route=$(this).data('url');
    axios.get(route)
    .then((data)=>{
      var editKeys=Object.keys(data.data);
      editKeys.forEach(function(key){
        if(key=='name'){
          $('#'+'name').val(data.data[key]);
        }
         $('#'+key).val(data.data[key]);
         $('#modal').modal('show');
         $('#id').val(data.data.id);
      })
    })
});
$(document).delegate(".deleteRow", "click", function(){
    let route=$(this).data('url');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value==true) {
        axios.delete(route)
        .then((data)=>{
          if(data.data.message){
            toastr.success(data.data.message);
            datatable.ajax.reload();
          }else if(data.data.warning){
            toastr.error(data.data.warning);
          }
        })
      }
    })
});
$('#user').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/search-user')}}",
      type:'post',
      dataType:'json',
      delay:20,
      data:function(params){
        return {
          searchTerm:params.term,
          _token:"{{csrf_token()}}",
          }
      },
      processResults:function(response){
        return {
          results:response,
        }
      },
      cache:true,
    }
  })
function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
  $('form select').val('').niceSelect('update');
}

// var valData;
// ClassicEditor
//     .create(document.querySelector('#content'))
//     .then(editor => {
//         editor.model.insertContent('asdflkjnksdjlkfj');
//         editor.model.document.on('change:data', () => {
//             valData = editor.getData();
            
//         });
//     })
//     .catch(error => {
//       console.error(error);
//     });
$(document).ready(function() {
  // $('#content').summernote({
  //   height: 300, // set editor height
  //   callbacks: {
  //     onImageUpload: function(files) {
  //       uploadImage(files[0]);
  //     }
  //   }
  // })
  showFolder()
  showImage();
  var input = document.querySelector('#focus_keyword');
  tagify = new Tagify(input, {
      
      maxTags: 10,
      dropdown: {
        maxItems: 20,           // <- mixumum allowed rendered suggestions
        classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
        enabled: 0,             // <- show suggestions on focus
        closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
      }
    })
})
  function uploadImage(file) {
    $('#content').summernote('insertImage','https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Image_created_with_a_mobile_phone.png/800px-Image_created_with_a_mobile_phone.png');
    // console.log(file)
      var formData = new FormData();
      formData.append('file', file);

      $.ajax({
        url: '/upload/image',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          $('#summernote').summernote('insertImage', response.imageUrl);
        }
      });
  }

  var imagesFiles=[];
  let ajaxConfig = {
        ajaxRequester: function (config, uploadFile, pCall, sCall, eCall) {
            console.log(uploadFile.file)
            if(imagesFiles.length<=4){
                console.log(imagesFiles.length)
                imagesFiles.push(uploadFile.file);
            }else{
                $('.jquery-uploader-select-card').addClass('d-none')
            }
            let progress = 0
            let interval = setInterval(() => {
                progress += 10;
                pCall(progress)
                if (progress >= 100) {
                    clearInterval(interval)
                    const windowURL = window.URL || window.webkitURL;
                    sCall({
                        data: windowURL.createObjectURL(uploadFile.file)
                    })
                    // eCall("上传异常")
                }
            }, 300)
        }
    }
  $("#file").uploader({
      multiple:false,
      ajaxConfig:ajaxConfig,
  })

  function createFolder(){
    name=$('#folder_name').val();
    axios.post("{{URL::to('news/folder')}}",{name:name})
    .then(res=>{
      console.log(res);
      if(res.data.message){
        toastr.success(res.data.message);
        $('#folder_name').val('');
        showFolder();
      }
    })
  }

  function showFolder(){
    axios.get("{{URL::to('news/get-folder')}}")
    .then(res=>{
      console.log(res);
      html="";
      res.data.forEach(function(d){
        // html+="<button class='btn btn-sm btn-warning mr-1'><i class='fas fa-folder'></i> "+d.name+"</button>"
        html+=`<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-warning ">`+d.name+`</button>
                  <button type="button" class="btn btn-sm btn-warning mr-1 dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(78px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item" href="#">Rename</a>
                    <a class="dropdown-item" href="#">Copy</a>
                    <a class="dropdown-item" href="#">Delete</a>
                  </div>
                </div>
                <input type='radio' name='folder[]' value='`+d.id+`'/>
                `
      })
      $('#all-folders').html(html)
    })
  }
  $(document).on('click','.file-delete',function(){
    imagesFiles=[];
  })
  function showImage(folder=null)
  {
    axios.get("{{URL::to('news/get-images')}}/"+folder)
    .then(res=>{
      html="";
      res.data.forEach(function(d){
        html+=`<div class="col-12 col-md-3">
                <img style="max-height:150px;" onclick='addImage(this.src)' src="{{asset('storage/media/images/news')}}/`+d.name+`" alt="image" class='img-fluid'>
               </div>`
      })
      $('.all-images').html(html);
    })
  }
  function addImage(url){
    console.log(url);
    $('.ck-content').text(url);
  }
  $(document).on('click','.removeAuthor',function(){
      event.preventDefault();
      console.log($(this).parent().remove());
  })
  $(document).on('click','#add_author',function(){
    user=$('#user').val();
     usertext=$('#user option:selected').text();
     console.log(usertext)
     html=`
     <div class='m-1'>
          <input type="hidden" value='`+user+`' name='author[]'>
          <span class>`+usertext+`</span>
          <button class="btn btn-xs btn-danger ml-1 float-right removeAuthor">X</button>
      </div>
     `
     $('#all_users').append(html)
  })

  $(document).on('focus','.ck-content',function(event){
    if (editorInstance) {
      const imageUrl = "https://upload.wikimedia.org/wikipedia/commons/b/b6/Image_created_with_a_mobile_phone.png"; // Replace with the actual image URL
      
      editorInstance.model.change(writer => {
        // Create a new image element and set its attributes
        const imageElement = writer.createElement('image', {
          src: imageUrl,
          alt: 'Image description',
        });
        console.log(imageElement)
        // Insert the image at the current cursor position
        editorInstance.model.insertContent(imageElement, editorInstance.model.document.selection.getFirstPosition())
      });
    }
  })
</script>
