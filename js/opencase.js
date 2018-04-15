(function ($, Drupal) {
  $(".field--name-contact-details a").contents().unwrap();
  $('#edit-search-api-fulltext').attr('autocomplete', 'off');
})(jQuery, Drupal);
