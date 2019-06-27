@section('scripts')
<script src="{{ asset('js/select.js') }}"> </script>
<script>
    $('#datetimepicker_begin,#datetimepicker_end').datepicker({
        format: "yyyy-mm-dd",
        language: "zh-CN",
        todayHighlight: true
    });

 	$('#morning_start_time,#morning_end_time,#afternoon_start_time,#afternoon_end_time').datetimepicker({
                format: 'hh:ii',
                autoclose: true,
                startView: 1,  
                minView: 0, 
                minuteStep:1,
                language: 'zh-CN'
    });

	function projectImage(id){
		    $('iframe#image').attr('src', '/filemanager/dialog.php?type=1&field_id=' + id);
            console.log(id);
	}

	function deletePic(id){
		$('#project_image_'+id).remove();
	}
</script>
@endsection