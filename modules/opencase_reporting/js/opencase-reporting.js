jQuery('body').on('click','#opencase_reporting_buttons #download_data',function(){
  var url = new URL(location.href);
  url.searchParams.append('_format', 'csv');
  location.href = url.toString();
});
