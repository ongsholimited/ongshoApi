<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('course.index')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'category',
            name:'category',
          },
          {
            data:'user',
            name:'user',
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
    $('input,select').removeClass('is-invalid');
    let category=$('#category').val();
    let user=$('#user').val();
    let title=$('#title').val();
    let slug=$('#slug').val();
    let description=$('#description').val();
    let thumbnail=document.getElementById('thumbnail').files;
    let id=$('#id').val();
    if (category==null) {
      category="";
    }
    let formData= new FormData();
    formData.append('category',category);
    formData.append('user',user);
    formData.append('title',title);
    formData.append('slug',slug);
    formData.append('description',description);
    if(thumbnail[0]!=null){
      formData.append('thumbnail',thumbnail[0]);
    }
    $('#exampleModalLabel').text('Edit Course');
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('course.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                datatable.ajax.reload();
                clear();
                $('#modal').modal('hide');
            }else if(response.data.error){
              var keys=Object.keys(response.data.error);
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid');
                $('#'+d+'_msg').text(response.data.error[d][0]);
              })
            }
        })
    }else{
      axios.post("{{URL::to('institute/course/')}}/"+id,formData)
        .then(function (response){
          if(response.data.message){
              toastr.success(response.data.message);
              datatable.ajax.reload();
              clear();
          }else if(response.data.error){
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


$('#category').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/institute/get-category')}}",
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
function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
  $('form select').val(null).trigger('change');
  $('input').val('')
  $('textarea').text('')
}

function slugx(title){
  var str = title; // the string to be modified
  var regex = / /g; // the regex that matches space
  var newStr = str.replace(regex, "-"); // the new string with hyphens
  $('#slug').val(newStr);
}
</script>
