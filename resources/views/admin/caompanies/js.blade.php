@section('scripts')
<script src="{{ asset('js/select.js') }}"> </script>
<script>
	function companyImage(id){
		    $('iframe#image').attr('src', '/filemanager/dialog.php?type=1&field_id=' + id);
            console.log(id);
	}

	function deletePic(id){
		// id=id.toString();
		// console.log(id);
		$('#company_image_'+id).remove();
	}
</script>
@endsection