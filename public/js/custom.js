$.ajax({
    url: '/test',
    type: 'get',
    data: {'q':'', '_token': $('input[name=_token]').val()},
    success: function(data){
        console.log(data);
    },
    error: function(data){
        // Not found
    }
});
