jQuery(document).ready(function ($) {
  $("#country").select2();
  $("#rca_selected_country").select2();
  $("#rca_page_id").select2({
    placeholder: "Select Page",
    allowClear: true,
    width: "50%",
    ajax: {
      url: ajaxurl,
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          search_key: params.term,
          action: "rca_get_posts",
        };
      },
      processResults: function (data) {
        var options = [];
        if (data) {
          $.each(data, function (index, text) {
            options.push({ id: text[0], text: text[1] });
          });
        }
        return {
          results: options,
        };
      },
      cache: true,
    },
  });
});
