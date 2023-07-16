<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('news.category.index')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'name',
            name:'name',
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
    let parent_category=$('#parent_category').val();
    let name=$('#name').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('parent_category',parent_category);
    formData.append('name',name);
    $('#exampleModalLabel').text('Add New Category');
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('news.category.store')}}",formData)
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
      axios.post("{{URL::to('category/')}}/"+id,formData)
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
    $('#exampleModalLabel').text('Add New Category');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Update Category');
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
$('#parent_category').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/news/get-category')}}",
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
</script>
