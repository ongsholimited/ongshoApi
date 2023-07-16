<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('content.index')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'course',
            name:'course',
          },
          {
            data:'chapter',
            name:'chapter',
          },
          {
            data:'title',
            name:'title',
          },
          {
            data:'thumbnail',
            name:'thumbnail',
          },
          {
            data:'action',
            name:'action',
          }
        ]
    });
  })
    

window.formRequest= function(){
    submitBtnClick();
    $('input,select').removeClass('is-invalid');
    let user=$('#user').val();
    let course=$('#course').val();
    let chapter=$('#chapter').val();
    let title=$('#title').val();
    let thumbnail=document.getElementById('thumbnail').files
    let video=document.getElementById('video').files;
    let id=$('#id').val();
    if (course==null) {
      course="";
    }
    if (chapter==null) {
      chapter="";
    }
    let formData= new FormData();
    formData.append('user',user);
    formData.append('course',course);
    formData.append('chapter',chapter);
    formData.append('title',title);
    if(thumbnail[0]!=null){
      formData.append('thumbnail',thumbnail[0]);
    }
    if(video[0]!=null){
      formData.append('video',video[0]);
    }
    $('#exampleModalLabel').text('Edit Course');
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    // progress event()
    const options={
      onUploadProgress:(progressEvent)=>{
        const {loaded,total}=progressEvent;
        let percent=Math.floor((loaded*100)/total);
        console.log( loaded,total,percent);
        $('.progress').removeClass('d-none')
        $('.progress-bar').css('width',percent+'%');
        if(percent==100){
          setTimeout(() => {
            $('.progress').addClass('d-none');
          }, 750);
        }
      }
    }
    if (id==''){
         axios.post("{{route('content.store')}}",formData,options)
        .then(function (response){
            if(response.data.message){
                submitComplete()
                toastr.success(response.data.message)
                datatable.ajax.reload();
                clear();
                $('#modal').modal('hide');
            }else if(response.data.error){
              submitComplete()
              var keys=Object.keys(response.data.error);
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid');
                $('#'+d+'_msg').text(response.data.error[d][0]);
              })
            }
        })
    }else{
      axios.post("{{URL::to('institute/content/')}}/"+id,formData)
        .then(function (response){
          submitComplete()
          if(response.data.message){
              toastr.success(response.data.message);
              datatable.ajax.reload();
              clear();
          }else if(response.data.error){
              submitComplete()
              var keys=Object.keys(response.data.error);
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid')
                $('#'+d+'_msg').text(response.data.error[d][0]);
              })
            }
        })
    }
}
$(document).delegate("#modalBtn", "click", function(event){
    clear();
    $('#exampleModalLabel').text('Add New Course');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Update Course');
    let route=$(this).data('url');
    axios.get(route)
    .then((data)=>{
      var editKeys=Object.keys(data.data);
      editKeys.forEach(function(key){
        // if(key=='user'){
        //   $('#'+'user').html("<option value='"+data.data[key].id+"'>"+data.data[key].name+"</option>");
        // }
        if(key!='thumbnail'){
          $('#'+key).val(data.data[key]);
        }
         $('#modal').modal('show');
         $('#id').val(data.data.id);
         if(key=='user'){
           console.log(data.data[key].id)
          $('#'+'user').html("<option value='"+data.data[key].id+"'>"+data.data[key].first_name+' '+data.data[key].last_name+"</option>");
        }
        if(key=='category'){
           console.log(data.data[key].id)
          $('#'+'category').html("<option value='"+data.data[key].id+"'>"+data.data[key].name+"</option>");
        }
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


$('#chapter').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/institute/get-chapter')}}",
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
  $('#user').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/institute/get-user-data')}}",
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
  $('#course').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/institute/get-course')}}",
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
  $('form select').val(null).trigger('change');
  $('input').val('')
  $('textarea').text('')
}
function submitBtnClick(){
  html="<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>Loading..."
  $('#submitBtn').html(html);
  $('#submitBtn').attr('disabled',true);
}

function submitComplete(){
  html='Save';
  $('#submitBtn').html(html);
  $('#submitBtn').attr('disabled',false);
}



</script>
